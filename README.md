# PHP Chrome HTML to PDF

A PHP library for converting HTML to PDF using Google Chrome.

[![Build Status](https://travis-ci.org/spiritix/php-chrome-html2pdf.svg?branch=master)](https://travis-ci.org/spiritix/php-chrome-html2pdf)
[![Code Climate](https://codeclimate.com/github/spiritix/php-chrome-html2pdf/badges/gpa.svg)](https://codeclimate.com/github/spiritix/php-chrome-html2pdf)
[![Total Downloads](https://poser.pugx.org/spiritix/php-chrome-html2pdf/d/total.svg)](https://packagist.org/packages/spiritix/php-chrome-html2pdf)
[![Latest Stable Version](https://poser.pugx.org/spiritix/php-chrome-html2pdf/v/stable.svg)](https://packagist.org/packages/spiritix/php-chrome-html2pdf)
[![Latest Unstable Version](https://poser.pugx.org/spiritix/php-chrome-html2pdf/v/unstable.svg)](https://packagist.org/packages/spiritix/php-chrome-html2pdf)
[![License](https://poser.pugx.org/spiritix/php-chrome-html2pdf/license.svg)](https://packagist.org/packages/spiritix/php-chrome-html2pdf)

## How it works

This library is based on [puppeteer](https://github.com/GoogleChrome/puppeteer), a headless Chrome Node API which is 
maintained by the Chrome DevTools team.

It provides a simple PHP wrapper around the Node API, focused on generating beautiful PDF files.

In contrast to other HTML to PDF converters like [wkhtmltopdf](https://wkhtmltopdf.org/), the corresponding 
[PHP wrapper](https://github.com/spiritix/html-to-pdf) or similar libraries, it is based on a current Chrome version 
instead of outdated and unmaintained WebKit builds. This library therefore fully supports CSS3, HTML5, SVGs, SPAs, 
and all the other fancy stuff people use these days.

## Requirements

- PHP 7.0+ with enabled program execution functions (proc_open) and 'fopen wrappers'
- Node.js 7.6+
- A few [OS specific dependencies](https://github.com/GoogleChrome/puppeteer/blob/master/docs/troubleshooting.md)

## Installation

PHP Chrome HTML to PDF can be installed via [Composer](http://getcomposer.org) by requiring the
`spiritix/php-chrome-html2pdf` package in your project's `composer.json`.
Or simply run this command:

```sh
composer require spiritix/php-chrome-html2pdf
```

The required JS packages are installed automatically in the background.

## Usage

The usage of this library is pretty simple. 
You just need a converter instance, pass an input and an output handler to it and set some options if you like.
After running the conversion, the converter will provide you with the output handler instance.
Now you may use it's specific functionality to get your PDF file.

```php
use Spiritix\Html2Pdf\Converter;
use Spiritix\Html2Pdf\Input\UrlInput;
use Spiritix\Html2Pdf\Output\DownloadOutput;

$input = new UrlInput();
$input->setUrl('https://www.google.com');

$converter = new Converter($input, new DownloadOutput());

$converter->setOption('landscape', true);

$converter->setOptions([
    'printBackground' => true,
    'headerTemplate' => '<p>I am a header</p>',
]);

$output = $converter->convert();
$output->download();
```

### Input handlers

The following input handlers are available:

- StringInput - Accepts the PDF content as a string
- UrlInput - Fetches the PDF content from an URL

### Output handlers

The following output handlers are available:

- StringOutput - Returns the binary PDF content as a string
- FileOutput - Stores the PDF file on the server's file system
- DownloadOutput - Forces the browser to download the PDF file
- EmbedOutput - Forces the browser to embed the PDF file

## Options

- `scale` <[number]> Scale of the webpage rendering. Defaults to `1`.
- `displayHeaderFooter` <[boolean]> Display header and footer. Defaults to `false`.
- `headerTemplate` <[string]> HTML template for the print header. Should be valid HTML markup with following classes used to inject printing values into them:
    - `date` formatted print date
    - `title` document title
    - `url` document location
    - `pageNumber` current page number
    - `totalPages` total pages in the document
- `footerTemplate` <[string]> HTML template for the print footer. Should use the same format as the `headerTemplate`.
- `printBackground` <[boolean]> Print background graphics. Defaults to `false`.
- `landscape` <[boolean]> Paper orientation. Defaults to `false`.
- `pageRanges` <[string]> Paper ranges to print, e.g., '1-5, 8, 11-13'. Defaults to the empty string, which means print all pages.
- `format` <[string]> Paper format. If set, takes priority over `width` or `height` options. Defaults to 'Letter'.
- `width` <[string]> Paper width, accepts values labeled with units.
- `height` <[string]> Paper height, accepts values labeled with units.
- `margin` <[Object]> Paper margins, defaults to none.
    - `top` <[string]> Top margin, accepts values labeled with units.
    - `right` <[string]> Right margin, accepts values labeled with units.
    - `bottom` <[string]> Bottom margin, accepts values labeled with units.
    - `left` <[string]> Left margin, accepts values labeled with units.

## Contributing

Contributions in any form are welcome.
Please consider the following guidelines before submitting pull requests:

- **Coding standard** - It's mostly PSR. 
- **Add tests!** - Your PR won't be accepted if it doesn't have tests.

## License

PHP Chrome HTML to PDF is free software distributed under the terms of the MIT license.