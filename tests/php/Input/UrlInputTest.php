<?php

namespace Spiritix\Html2Pdf\Tests\Input;

use Spiritix\Html2Pdf\Input\UrlInput;
use Spiritix\Html2Pdf\Tests\TestCase;

class UrlInputTest extends TestCase
{
    public function testSetUrl()
    {
        $input = new UrlInput();
        $input->setUrl('https://www.google.com');

        $this->assertContains('Google', $input->getHtml());
    }
}