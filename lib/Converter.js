/**
 * This file is part of the spiritix/php-chrome-html2pdf package.
 *
 * @copyright Copyright (c) Matthias Isler <mi@matthias-isler.ch>
 * @license   MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

const puppeteer = require('puppeteer');

const defaultOptions = {
    //path: 'node-output.pdf',
    format: 'A4',
    printBackground: true
};

class Converter
{
    constructor(html, options)
    {
        this.html = html;
        this.options = options;
    }

    getDefaultOptions()
    {
        return defaultOptions;
    }

    getOptions()
    {
        return {
            ...defaultOptions,
            ...this.options
        };
    }

    async run()
    {
        const browser = await puppeteer.launch({
            ignoreHTTPSErrors: true
        });

        const page = await browser.newPage();

        // Currently not working because of bugs in puppeteer :/
        //
        // @see https://github.com/GoogleChrome/puppeteer/issues/811
        // @see https://github.com/GoogleChrome/puppeteer/issues/728
        //
        // await page.setContent(this.html);
        // await page.waitForNavigation({waitUntil: ['load', 'networkidle0']});
        //
        // Using the following workaround instead:
        await page.goto(`data:text/html,${this.html}`, {
            waitUntil: ['load', 'networkidle0']
        });

        const buffer = await page.pdf(this.getOptions());
        await browser.close();

        return buffer;
    }
}

module.exports = Converter;