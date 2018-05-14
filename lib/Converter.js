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

    run()
    {
        return new Promise((resolve) => {
            this._launchBrowser().then((browser) => {

                this._initPage(browser)
                    .then((page) => this._convert(page))
                    .then((buffer) => {

                        this._close(browser);
                        resolve(buffer);
                    });
            });
        });
    }

    _launchBrowser()
    {
        return puppeteer.launch({
            ignoreHTTPSErrors: true,
            args: ['--no-sandbox']
        });
    }

    _initPage(browser)
    {
        return browser.newPage();
    }

    _convert(page)
    {
        let options = this.getOptions();

        if (options.hasOwnProperty('mediaType')) {
            page.emulateMedia(options.mediaType);
            delete options.mediaType;
        }

        if (options.hasOwnProperty('viewport')) {
            mediaType = page.setViewport(options.viewport);
            delete options.viewport;
        }

        return this._setHtml(page, this.html)
            .then(() => this._getPdf(page, options));
    }

    _setHtml(page, html)
    {
        // Currently not working because of bugs in puppeteer :/
        //
        // @see https://github.com/GoogleChrome/puppeteer/issues/811
        // @see https://github.com/GoogleChrome/puppeteer/issues/728
        //
        // await page.setContent(this.html);
        // await page.waitForNavigation({waitUntil: ['load', 'networkidle0']});
        //
        // Using the following workaround instead:
        //
        return page.goto(`data:text/html,${html}`, {
            waitUntil: ['load', 'networkidle0']
        });
    }

    _getPdf(page, options)
    {
        return page.pdf(options)
    }

    _close(browser)
    {
        browser.close();
    }
}

module.exports = Converter;
