<?php

namespace Spiritix\Html2Pdf\Tests\Output;

use Spiritix\Html2Pdf\Output\FileOutput;
use Spiritix\Html2Pdf\Tests\TestCase;

class FileOutputTest extends TestCase
{
    public function testStore()
    {
        $pdfData = $this->getPdfSampleData();

        $output = new FileOutput();
        $output->setPdfData($pdfData);

        $url = '/tmp/sample.pdf';
        $output->store($url);

        $this->assertEquals(file_get_contents($url), $pdfData);
    }
}