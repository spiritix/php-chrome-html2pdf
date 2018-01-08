<?php

namespace Spiritix\Html2Pdf\Tests\Output;

use Spiritix\Html2Pdf\Output\DownloadOutput;
use Spiritix\Html2Pdf\Tests\TestCase;

class DownloadOutputTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testDownload()
    {
        $pdfData = $this->getPdfSampleData();

        $output = new DownloadOutput();
        $output->setPdfData($pdfData);

        ob_start();
        $output->download('sample.pdf', false);
        $data = ob_get_clean();

        $this->assertEquals($pdfData, $data);
    }
}