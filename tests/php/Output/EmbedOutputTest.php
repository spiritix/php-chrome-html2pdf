<?php

namespace Spiritix\Html2Pdf\Tests\Output;

use Spiritix\Html2Pdf\Output\EmbedOutput;
use Spiritix\Html2Pdf\Tests\TestCase;

class EmbedOutputTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testEmbed()
    {
        $pdfData = $this->getPdfSampleData();

        $output = new EmbedOutput();
        $output->setPdfData($pdfData);

        ob_start();
        $output->embed('sample.pdf', false);
        $data = ob_get_clean();

        $this->assertEquals($pdfData, $data);
    }
}