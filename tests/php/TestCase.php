<?php

namespace Spiritix\Html2Pdf\Tests;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    public function getPdfSampleData()
    {
        return file_get_contents(__DIR__ . '/pdf/Sample.pdf');
    }
}