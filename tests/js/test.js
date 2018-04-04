const expect = require('chai').expect;
const assert = require('chai').assert;
const Converter = require('../../lib/Converter');

describe('Converter', () => {

    describe('getDefaultOptions', () => {
        it('returns an object', () => {
            const converter = new Converter('', {});
            assert.isObject(converter.getDefaultOptions());
        });
    });

    describe('getOptions', () => {
        it('returns the merged options object', () => {
            const options = {printBackground: false, landscape: true};
            const converter = new Converter('', options);

            expect(converter.getOptions()).to.deep.equal(
                Object.assign({}, converter.getDefaultOptions(), options)
            );
        });
    });

    describe('run', () => {
        it('returns a buffer', async () => {
            const converter = new Converter('<p>Hello</p>', {});
            expect(await converter.run()).to.be.instanceof(Buffer);
        });
    });

});