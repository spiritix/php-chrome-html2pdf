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
 * Abstract output handler.
 *
 * @package Spiritix\Html2Pdf\Output
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
abstract class AbstractOutput implements OutputInterface
{
    /**
     * Contains the PDF binary data.
     *
     * @var null|string
     */
    private $pdfData = null;

    /**
     * Must accept the PDF binary data as an argument.
     *
     * @param string $data The binary PDF data
     */
    public function setPdfData(string $data)
    {
        $this->pdfData = $data;
    }

    /**
     * Returns the PDF binary data.
     *
     * @throws OutputException If data has not yet been set
     *
     * @return string
     */
    protected function getPdfData(): string
    {
        if ($this->pdfData === null) {
            throw new OutputException('PDF data has not yet been set');
        }

        return $this->pdfData;
    }
}