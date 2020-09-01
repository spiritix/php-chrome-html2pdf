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
 * Output handler for forcing the browser to download the PDF file.
 *
 * @package Spiritix\Html2Pdf\Output
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
class DownloadOutput extends AbstractOutput
{
    /**
     * Force the browser to download the PDF file.
     *
     * @throws OutputException If headers have already been sent
     * @throws OutputException If the specified file name is invalid
     *
     * @param string $fileName The name of the file to be downloaded
     * @param bool   $exit     If execution should be finished after download
     */
    public function download(string $fileName, bool $exit = true)
    {
        if (headers_sent()) {
            throw new OutputException('Headers have already been sent');
        }

        if (empty($fileName)) {
            throw new OutputException('Please specify a valid filename');
        }

        header('Content-Description: File Transfer');
        header('Cache-Control: public; must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d m Y H:i:s') . ' GMT');
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream', false);
        header('Content-Type: application/download', false);
        header('Content-Type: application/pdf', false);
        header('Content-Disposition: attachment; filename="' . basename($fileName) .'"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($this->getPdfData()));

        echo $this->getPdfData();

        if ($exit === true) {
            exit;
        }
    }
}
