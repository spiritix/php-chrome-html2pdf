/**
 * This file is part of the spiritix/php-chrome-html2pdf package.
 *
 * @copyright Copyright (c) Matthias Isler <mi@matthias-isler.ch>
 * @license   MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

const getStdin = require('get-stdin');
const program = require('commander');
const Converter = require('./lib/Converter');

(async () => {
    program
        .option('-o, --options [options]', 'PDF options for puppeteer')
        .parse(process.argv);

    const options = JSON.parse(program.options);

    const converter = new Converter(await getStdin(), options);
    const buffer = await converter.run();

    process.stdout.write(buffer.toString('binary'), 'binary');
})();