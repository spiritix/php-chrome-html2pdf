<?php

namespace Spiritix\Html2Pdf\Tests\Input;

use Spiritix\Html2Pdf\Input\StringInput;
use Spiritix\Html2Pdf\Tests\TestCase;

class StringInputTest extends TestCase
{
    public function testSetHtml()
    {
        $html = '<h1>Hello</h1>';

        $input = new StringInput();
        $input->setHtml($html);

        $this->assertEquals($html, $input->getHtml());
    }
}