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

class Converter
{
    constructor(html, options)
    {
        this.html = html;
        this.options = options;
    }

    getDefaultOptions()
    {
        return {
            path: 'node-output.pdf',
            format: 'A4',
            printBackground: true
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
        //await page.setContent(this.html);
        //await page.waitForNavigation({waitUntil: ['load', 'networkidle0']});

        await page.goto(`data:text/html,${this.html}`, {
            waitUntil: ['load', 'networkidle0']
        });

        const options = Object.assign(this.getDefaultOptions(), this.options);
        const buffer = await page.pdf(options);

        await browser.close();

        return buffer;
    }
}

module.exports = Converter;