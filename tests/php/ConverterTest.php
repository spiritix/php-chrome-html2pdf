<?php

namespace Spiritix\Html2Pdf\Tests;

use Spiritix\Html2Pdf\Converter;
use Spiritix\Html2Pdf\Input\StringInput;
use Spiritix\Html2Pdf\Output\StringOutput;

class ConverterTest extends TestCase
{
    /**
     * @var Converter
     */
    private $converter;

    public function setUp(): void
    {
        parent::setUp();

        $input = new StringInput();
        $input->setHtml('<h1>Hello</h1>');

        $this->converter = new Converter($input, new StringOutput());
    }

    public function testOptions()
    {
        $this->converter->setOption('printBackground', false);
        $this->converter->setOptions([
            'landscape' => true,
            'headerTemplate' => '<p>Hello</p>',
        ]);

        $value = $this->converter->getOption('printBackground');
        $this->assertEquals(false, $value);

        $value = $this->converter->getOption('landscape');
        $this->assertEquals(true, $value);

        $value = $this->converter->getOption('headerTemplate');
        $this->assertEquals('<p>Hello</p>', $value);

        $options = $this->converter->getOptions();
        $this->assertEquals([
            'printBackground' => false,
            'landscape' => true,
            'headerTemplate' => '<p>Hello</p>',
        ], $options);
    }

    public function testNodePath()
    {
        $path = '/path/to/node';
        $this->converter->setNodePath($path);

        $this->assertEquals($path, $this->converter->getNodePath());
    }

    public function testConvert()
    {
        $output = $this->converter->convert();
        $this->assertInstanceOf(StringOutput::class, $output);
    }
}