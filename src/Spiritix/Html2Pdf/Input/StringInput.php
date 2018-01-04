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

namespace Spiritix\Html2Pdf\Input;

/**
 * Input handler for providing HTML markup as a string.
 *
 * @package Spiritix\Html2Pdf\Input
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
class StringInput extends AbstractInput
{
    /**
     * Set the HTML markup.
     *
     * @param string $html HTML markup
     *
     * @throws InputException If input is empty
     * @throws InputException If input is not valid HTML
     */
    public function setHtml(string $html)
    {
        if (empty($html)) {
            throw new InputException('Input is empty');
        }

        if ($html === strip_tags($html)) {
            throw new InputException('Input must be valid HTML markup');
        }

        $this->html = $html;
    }
}