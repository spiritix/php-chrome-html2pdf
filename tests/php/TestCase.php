<?php

namespace Spiritix\Html2Pdf\Tests;

use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
    public function getPdfSampleData()
    {
        return file_get_contents(__DIR__ . '/pdf/Sample.pdf');
    }
}