<?php

namespace Spiritix\Html2Pdf\Tests\Output;

use Spiritix\Html2Pdf\Output\StringOutput;
use Spiritix\Html2Pdf\Tests\TestCase;

class StringOutputTest extends TestCase
{
    public function testGet()
    {
        $pdfData = $this->getPdfSampleData();

        $output = new StringOutput();
        $output->setPdfData($pdfData);

        $this->assertEquals($pdfData, $output->get());
    }
}