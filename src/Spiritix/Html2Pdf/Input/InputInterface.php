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
 * Input handler interface.
 *
 * @package Spiritix\Html2Pdf\Input
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
interface InputInterface
{
    /**
     * Must return the input HTML markup.
     *
     * @return string
     */
    public function getHtml(): string;
}