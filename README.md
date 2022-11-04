# PHP Chrome HTML to PDF

A PHP library for converting HTML to PDF using Chrome/Chromium.

[![PHP Checks](https://github.com/spiritix/php-chrome-html2pdf/actions/workflows/php.yml/badge.svg)](https://github.com/spiritix/php-chrome-html2pdf/actions/workflows/php.yml)
[![Node.js Checks](https://github.com/spiritix/php-chrome-html2pdf/actions/workflows/js.yml/badge.svg)](https://github.com/spiritix/php-chrome-html2pdf/actions/workflows/js.yml)
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

- PHP 8.0+ with enabled program execution functions (proc_open) and 'fopen wrappers'
- Node.js 14.1.0+ (for older Node.js versions use see [changelog](https://github.com/spiritix/php-chrome-html2pdf/blob/master/CHANGELOG.md))
- A few [OS specific dependencies](https://github.com/puppeteer/puppeteer/blob/main/docs/troubleshooting.md#chrome-headless-doesnt-launch-on-unix)

## Installation

PHP Chrome HTML to PDF can be installed via [Composer](http://getcomposer.org) by requiring the
`spiritix/php-chrome-html2pdf` package in your project's `composer.json`.
Or simply run this command:

```sh
composer require spiritix/php-chrome-html2pdf
```

The required JS packages are installed automatically in the background.

## Usage

Using this library is pretty straight forward. Decide for an input and an output handler, pass them to the converter, 
set some options if you like and depending on the output handler, proceed with the generated PDF file.

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
    'displayHeaderFooter' => true,
    'headerTemplate' => '<p>I am a header</p>',
]);

$output = $converter->convert();
$output->download('google.pdf');
```

### Input handlers

The following input handlers are available:

- StringInput - Accepts the HTML content as a string
- UrlInput - Fetches the HTML content from an URL

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
- `margin` <[array]> Paper margins, defaults to none.
    - `top` <[string]> Top margin, accepts values labeled with units.
    - `right` <[string]> Right margin, accepts values labeled with units.
    - `bottom` <[string]> Bottom margin, accepts values labeled with units.
    - `left` <[string]> Left margin, accepts values labeled with units.
- `preferCSSPageSize` <[boolean]> Give any CSS @page size declared in the page priority over what is declared in width and height or format options. Defaults to `false`, which will scale the content to fit the paper size.
- `omitBackground` <[boolean]> Hides default white background and allows capturing screenshots with transparency. Defaults to `false`.
- `timeout` <[number]> Maximum time in milliseconds, defaults to 30 seconds, pass 0 to disable timeout.
- `mediaType` <?[string]> Changes the CSS media type of the page. The only allowed values are `'screen'`, `'print'` and `null`. Passing `null` disables media emulation.
- `viewport` <[array]>
    - `width` <[number]> page width in pixels.
    - `height` <[number]> page height in pixels.
    - `deviceScaleFactor` <[number]> Specify device scale factor (can be thought of as dpr). Defaults to `1`.
    - `isMobile` <[boolean]> Whether the `meta viewport` tag is taken into account. Defaults to `false`.
    - `hasTouch`<[boolean]> Specifies if viewport supports touch events. Defaults to `false`
    - `isLandscape` <[boolean]> Specifies if viewport is in landscape mode. Defaults to `false`.
- `pageWaitFor` <[integer]> Timeout in milliseconds to wait for.
- `cookies`<[array]> Cookie objects to set.
    
> **NOTE** `headerTemplate` and `footerTemplate` markup have the following limitations:
> 1. Script tags inside templates are not evaluated.
> 2. Page styles are not visible inside templates.

> **NOTE** By default, this library generates a pdf with modified colors for printing. Use the `-webkit-print-color-adjust`  property to force rendering of exact colors.

## Troubleshooting

- Make sure you've installed all [OS specific dependencies](https://github.com/puppeteer/puppeteer/blob/main/docs/troubleshooting.md#chrome-headless-doesnt-launch-on-unix).
- Manually set the path to your Node.js executable using the `$converter->setNodePath()` method.

## Contributing

Contributions in any form are welcome.
Please consider the following guidelines before submitting pull requests:

- **Coding standard** - It's mostly PSR. 
- **Add tests!** - Your PR won't be accepted if it doesn't have tests.

## License

PHP Chrome HTML to PDF is free software distributed under the terms of the MIT license.
