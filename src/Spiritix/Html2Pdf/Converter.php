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

use Spiritix\Html2Pdf\Input\InputInterface;
use Spiritix\Html2Pdf\Output\OutputInterface;

/**
 * The actual HTML to PDF converter.
 *
 * @package Spiritix\Html2Pdf
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
class Converter
{
    /**
     * Name of the JS binary.
     */
    const JS_BINARY = 'index';

    /**
     * Input instance.
     *
     * @var InputInterface
     */
    private $input;

    /**
     * Output instance.
     *
     * @var OutputInterface
     */
    private $output;

    /**
     * The currently set options.
     *
     * @var array
     */
    private $options = [];


    /**
     * The currently set launch arguments.
     *
     * @var array
     */
    private $launchOptions = [];

    /**
     * The path to the Node.js executable
     *
     * @var string
     */
    private $nodePath = 'node';

    /**
     * Initialize converter.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $options Shortcut for setting multiple options
     * @param array           $launchOptions Shortcut for setting puppeeter launch arguments
     */
    public function __construct(InputInterface $input, OutputInterface $output, array $options = [], array $launchOptions = [])
    {
        $this->input = $input;
        $this->output = $output;

        if (!empty($options)) {
            $this->setOptions($options);
        }
        if (!empty($launchOptions)) {
            $this->setLaunchOptions($launchOptions);
        }
    }

    /**
     * Returns all options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Returns a single option.
     *
     * @param string $key Option key
     *
     * @throws ConverterException If option has not yet been set
     *
     * @return mixed Option value
     */
    public function getOption(string $key)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        throw new ConverterException('Option "' . $key . '" has not been set');
    }

    /**
     * Sets a single option.
     *
     * @param string $key   Option key
     * @param mixed  $value Option value
     *
     * @throws ConverterException If option key is empty
     *
     * @return Converter
     */
    public function setOption(string $key, $value): Converter
    {
        if (empty($key)) {
            throw new ConverterException('Option key must not be empty');
        }

        $this->options[$key] = $value;

        return $this;
    }

    /**
     * Set multiple options at once.
     *
     * @param array $options Multiple options
     *
     * @throws ConverterException If options are empty
     *
     * @return Converter
     */
    public function setOptions(array $options): Converter
    {
        if (empty($options)) {
            throw new ConverterException('Provided options must not be empty');
        }

        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }

        return $this;
    }

    /**
     * Returns all launchOptions.
     *
     * @return array
     */
    public function getLaunchOptions(): array
    {
        return $this->launchOptions;
    }

    /**
     * Returns a single launchOption.
     *
     * @param string $key LaunchOption key
     *
     * @throws ConverterException If launchOption has not yet been set
     *
     * @return mixed LaunchOption value
     */
    public function getLaunchOption(string $key)
    {
        if (isset($this->launchOptions[$key])) {
            return $this->launchOptions[$key];
        }

        throw new ConverterException('LaunchOption "' . $key . '" has not been set');
    }

    /**
     * Sets a single launchOption.
     *
     * @param string $key   LaunchOption key
     * @param mixed  $value LaunchOption value
     *
     * @throws ConverterException If launchOption key is empty
     *
     * @return Converter
     */
    public function setLaunchOption(string $key, $value): Converter
    {
        if (empty($key)) {
            throw new ConverterException('LaunchOption key must not be empty');
        }

        $this->launchOptions[$key] = $value;

        return $this;
    }

    /**
     * Set multiple launchOptions at once.
     *
     * @param array $launchOptions Multiple launchOptions
     *
     * @throws ConverterException If launchOptions are empty
     *
     * @return Converter
     */
    public function setLaunchOptions(array $launchOptions): Converter
    {
        if (empty($launchOptions)) {
            throw new ConverterException('Provided launchOptions must not be empty');
        }

        foreach ($launchOptions as $key => $value) {
            $this->setLaunchOption($key, $value);
        }

        return $this;
    }

    /**
     * Returns the path to the Node.js executable.
     *
     * @return string
     */
    public function getNodePath(): string
    {
        return $this->nodePath;
    }

    /**
     * Set the path to the Node.js executable.
     *
     * @param string $path
     *
     * @return Converter
     */
    public function setNodePath(string $path): Converter
    {
        if (!empty($path)) {
            $this->nodePath = $path;
        }

        return $this;
    }

    /**
     * Runs the conversion.
     *
     * @throws ConverterException If a binary error occurred
     * @throws ConverterException If a shell error occurred
     * @throws ConverterException If no data was returned
     *
     * @return OutputInterface
     */
    public function convert(): OutputInterface
    {
        $result = ProcessUtil::executeShellCommand($this->buildCommand(), $this->input->getHtml());

        if (strpos(mb_strtolower($result['error']), 'error') !== false) {
            throw new ConverterException('Binary error: ' . $result['error']);
        }

        if ($result['result'] > 1) {
            throw new ConverterException('Shell error: ' . $result['result']);
        }

        if (mb_strlen($result['output']) === 0) {
            throw new ConverterException('No data returned');
        }

        $this->output->setPdfData($result['output']);

        return $this->output;
    }

    /**
     * Builds the shell command for calling the binary.
     *
     * @return string
     */
    private function buildCommand(): string
    {
        $options = ProcessUtil::escapeShellArgument(json_encode($this->getOptions(), JSON_UNESCAPED_SLASHES));
        $launchOptions = ProcessUtil::escapeShellArgument(json_encode($this->getLaunchOptions(), JSON_UNESCAPED_SLASHES));
        $command = $this->getBinaryPath() . ' -o ' . $options. ' -l ' . $launchOptions;

        return $command;
    }

    /**
     * Returns the absolute path to the binary.
     *
     * @return string
     */
    private function getBinaryPath(): string
    {
        return $this->getNodePath() .
            ' "' .
            dirname(__FILE__) .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR .
            self::JS_BINARY . '"';
    }
}
