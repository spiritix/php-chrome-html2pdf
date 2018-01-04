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

namespace Spiritix\Html2Pdf\Output;

/**
 * Output handler for embedding the PDF into the browser window.
 *
 * @package Spiritix\Html2Pdf\Output
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
class EmbedOutput extends AbstractOutput
{
    /**
     * Force the browser to embed the PDF file.
     *
     * @throws OutputException If headers have already been sent
     * @throws OutputException If the specified file name is invalid
     *
     * @param string $fileName The name of the file to be embedded
     * @param bool   $exit     If execution should be finished after embedding the PDF
     */
    public function embed(string $fileName, bool $exit = true)
    {
        if (headers_sent()) {
            throw new OutputException('Headers have already been sent');
        }

        if (empty($fileName)) {
            throw new OutputException('Please specify a valid filename');
        }

        header('Content-type: application/pdf');
        header('Cache-control: public, must-revalidate, max-age=0');
        header('Pragme: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d m Y H:i:s') . ' GMT');
        header('Content-Length: ' . strlen($this->getPdfData()));
        header('Content-Disposition: inline; filename="' . basename($fileName) .'";');

        echo $this->getPdfData();

        if ($exit === true) {
            exit;
        }
    }
}