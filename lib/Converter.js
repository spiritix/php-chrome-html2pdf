'use strict';

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

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

class Converter {
    constructor(html, options) {
        this.html = html;
        this.options = options;
    }

    getDefaultOptions() {
        return defaultOptions;
    }

    getOptions() {
        return Object.assign({}, defaultOptions, this.options);
    }

    run() {
        var _this = this;

        return _asyncToGenerator(function* () {
            let browser = yield _this._launchBrowser();
            let page = yield _this._initPage(browser);
            let buffer = yield _this._convert(page);

            yield _this._close(browser);

            return buffer;
        })();
    }

    _launchBrowser() {
        var _this2 = this;

        return _asyncToGenerator(function* () {
            let browser = null;

            if (_this2.options.hasOwnProperty('executablePath')) {
                browser = yield puppeteerCore.launch({
                    executablePath: _this2.options.executablePath,
                    ignoreHTTPSErrors: true,
                    args: ['--no-sandbox', '--disable-web-security', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-gpu']
                });
                delete _this2.options.executablePath;
            } else {
                browser = yield puppeteer.launch({
                    ignoreHTTPSErrors: true,
                    args: ['--no-sandbox', '--disable-web-security', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-gpu']
                });
            }

            return browser;
        })();
    }

    _initPage(browser) {
        return _asyncToGenerator(function* () {
            return browser.newPage();
        })();
    }

    _convert(page) {
        var _this3 = this;

        return _asyncToGenerator(function* () {
            let options = _this3.getOptions();

            if (options.hasOwnProperty('mediaType')) {
                yield page.emulateMedia(options.mediaType);
                delete options.mediaType;
            }

            if (options.hasOwnProperty('viewport')) {
                yield page.setViewport(options.viewport);
                delete options.viewport;
            }

            if (options.hasOwnProperty('cookies')) {
                yield page.setCookie(...options.cookies);
                delete options.cookies;
            }

            yield _this3._setHtml(page, _this3.html);

            if (options.hasOwnProperty('pageWaitFor')) {
                const timeout = parseInt(options.pageWaitFor);
                delete options.pageWaitFor;
                yield page.waitFor(timeout);
            }

            return _this3._getPdf(page, options);
        })();
    }

    _setHtml(page, html) {
        return _asyncToGenerator(function* () {
            return page.setContent(html, {
                waitUntil: ['load', 'networkidle0']
            });
        })();
    }

    _getPdf(page, options) {
        return _asyncToGenerator(function* () {
            return page.pdf(options);
        })();
    }

    _close(browser) {
        return _asyncToGenerator(function* () {
            return browser.close();
        })();
    }
}

module.exports = Converter;