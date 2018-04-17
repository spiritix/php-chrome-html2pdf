<?php
/**
 * This file is part of the spiritix/php-chrome-html2pdf package.
 *
 * @copyright Copyright (c) Matthias Isler <mi@matthias-isler.ch>
 * @license   MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spiritix\Html2Pdf;

/**
 * Provides helper functions for system program execution.
 *
 * @package Spiritix\Html2Pdf
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
class ProcessUtil
{
    /**
     * Escape a string to be used as a shell argument.
     *
     * @param string $argument The argument that will be escaped.
     *
     * @return string The escaped string.
     */
    public static function escapeShellArgument(string $argument): string
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return self::escapeShellArgumentWindows($argument);
        }
        else {
            return self::escapeShellArgumentLinux($argument);
        }
    }

    /**
     * Executes a shell command.
     *
     * @param string $command The command to be executed
     * @param string $input   The data to be provided through the input stream
     *
     * @return array
     */
    public static function executeShellCommand(string $command, string $input = ''): array
    {
        $result = [];

        $proc = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes
        );

        fwrite($pipes[0], $input);
        fclose($pipes[0]);

        $result['output'] = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $result['error'] = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $result['result'] = (int) proc_close($proc);

        return $result;
    }

    /**
     * Escape a string to be used as a shell argument on Linux.
     *
     * @see http://drupalcontrib.org/api/drupal/contributions!drush!includes!exec.inc/function/_drush_escapeshellarg_linux/7
     *
     * @param string $argument The argument that will be escaped.
     *
     * @return string The escaped string.
     */
    private static function escapeShellArgumentLinux(string $argument): string
    {
        // For single quotes existing in the string, we will "exit"
        // single-quote mode, add a \' and then "re-enter"
        // single-quote mode.  The result of this is that
        // 'quote' becomes '\''quote'\''
        $argument = preg_replace('/\'/', '\'\\\'\'', $argument);

        // Replace "\t", "\n", "\r", "\0", "\x0B" with a whitespace.
        // Note that this replacement makes this function work differently
        // than the built-in escapeshellarg in PHP on Linux, as these characters
        // usually are NOT replaced. However, this was done deliberately to be more
        // conservative when running this function on Windows
        // (this can happen when generating a command to run on a remote Linux server.)
        $argument = str_replace(["\t", "\n", "\r", "\0", "\x0B"], ' ', $argument);

        // Add surrounding quotes.
        $argument = "'" . $argument . "'";

        return $argument;
    }

    /**
     * Escape a string to be used as a shell argument on Windows.
     *
     * @see http://drupalcontrib.org/api/drupal/contributions!drush!includes!exec.inc/function/_drush_escapeshellarg_windows/7
     *
     * @param string $argument The argument that will be escaped.
     *
     * @return string The escaped string.
     */
    private static function escapeShellArgumentWindows(string $argument): string
    {
        // Double up existing backslashes
        $argument = preg_replace('/\\\/', '\\\\\\\\', $argument);

        // Double up double quotes
        $argument = preg_replace('/"/', '""', $argument);

        // Double up percents.
        $argument = preg_replace('/%/', '%%', $argument);

        // Add surrounding quotes.
        $argument = '"' . $argument . '"';

        return $argument;
    }
}