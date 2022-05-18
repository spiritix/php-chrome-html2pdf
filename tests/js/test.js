import chai from 'chai'
import Converter from '../../lib/Converter.js';

describe('Converter', () => {

    describe('getDefaultOptions', () => {
        it('returns an object', () => {
            const converter = new Converter('', {});
            chai.assert.isObject(converter.getDefaultOptions());
        });
    });

    describe('getOptions', () => {
        it('returns the merged options object', () => {
            const options = {printBackground: false, landscape: true};
            const converter = new Converter('', options);

            chai.expect(converter.getOptions()).to.deep.equal(
                Object.assign({}, converter.getDefaultOptions(), options)
            );
        });
    });

    describe('run', () => {
        it('returns a buffer', async () => {
            const converter = new Converter('<p>Hello</p>', {});
            chai.expect(await converter.run()).to.be.instanceof(Buffer);
        });
    });

});