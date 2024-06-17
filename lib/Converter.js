/**
 * This file is part of the spiritix/php-chrome-html2pdf package.
 *
 * @copyright Copyright (c) Matthias Isler <mi@matthias-isler.ch>
 * @license   MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import puppeteer from 'puppeteer';

const defaultOptions = {};
const defaultLaunchOptions = {
        ignoreHTTPSErrors: true,
        headless: 'new',
        args: [
                '--no-sandbox',
                '--disable-web-security',
                '--font-render-hinting=none',
                '--proxy-server="direct://"',
                '--proxy-bypass-list=*',
            ]
        };

class Converter
{
    constructor(html, options, launchOptions)
    {
        this.html = html;
        this.options = options;
        this.launchOptions = launchOptions;
    }

    getDefaultOptions()
    {
        return defaultOptions;
    }

    getOptions()
    {
        return Object.assign({}, defaultOptions, this.options);
    }

    getLaunchOptions()
    {
        if(this.launchOptions === null) {
            return defaultLaunchOptions;
        }
        return this.launchOptions;
    }

    async run()
    {
        const browser = await this._launchBrowser();
        const page = await this._initPage(browser);
        const buffer = await this._convert(page);

        await this._close(browser);

        return buffer;
    }

    async _launchBrowser()
    {
        return puppeteer.launch(this.getLaunchOptions());
    }

    async _initPage(browser)
    {
        return browser.newPage();
    }

    async _convert(page)
    {
        let options = this.getOptions();
        if (options.hasOwnProperty('mediaType')) {
            await page.emulateMediaType(options.mediaType);
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
            await page.waitForTimeout(parseInt(options.pageWaitFor));
            delete options.pageWaitFor;
        }

        return this._getPdf(page, options)
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

export default Converter;
