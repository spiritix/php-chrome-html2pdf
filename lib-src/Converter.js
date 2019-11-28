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
const puppeteerCore = require('puppeteer-core');

const defaultOptions = {};

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
        return Object.assign({}, defaultOptions, this.options);
    }

    async run()
    {
        let browser = await this._launchBrowser();
        let page = await this._initPage(browser);
        let buffer = await this._convert(page);

        await this._close(browser);

        return buffer;
    }

    async _launchBrowser()
    {
        let browser = null;

        if (this.options.hasOwnProperty('executablePath')) {
            browser = await puppeteerCore.launch({
                executablePath: this.options.executablePath,
                ignoreHTTPSErrors: true,
                args: ['--no-sandbox', '--disable-web-security', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-gpu']
            });
            delete this.options.executablePath;
        } else {
            browser = await puppeteer.launch({
                ignoreHTTPSErrors: true,
                args: ['--no-sandbox', '--disable-web-security', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-gpu']
            });
        }

        return browser;
    }

    async _initPage(browser)
    {
        return browser.newPage();
    }

    async _convert(page)
    {
        let options = this.getOptions();

        if (options.hasOwnProperty('mediaType')) {
            await page.emulateMedia(options.mediaType);
            delete options.mediaType;
        }

        if (options.hasOwnProperty('viewport')) {
            await page.setViewport(options.viewport);
            delete options.viewport;
        }

        if (options.hasOwnProperty('cookies')) {
            await page.setCookie(...options.cookies);
            delete options.cookies;
        }

        await this._setHtml(page, this.html);

        if (options.hasOwnProperty('pageWaitFor')) {
            const timeout = parseInt(options.pageWaitFor);
            delete options.pageWaitFor;
            await page.waitFor(timeout);
        }

        return this._getPdf(page, options);
    }

    async _setHtml(page, html)
    {
        return page.setContent(html, {
            waitUntil: ['load', 'networkidle0']
        });
    }

    async _getPdf(page, options)
    {
        return page.pdf(options);
    }

    async _close(browser)
    {
        return browser.close();
    }
}

module.exports = Converter;
