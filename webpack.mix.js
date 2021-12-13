const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const env = process.env;

const isTestNet = env.IS_TESTNET;

mix.webpackConfig((webpack) => ({
    output: {
        publicPath: '/',
        chunkFilename: 'js/components/[name].[hash].js',
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                WEB3X: JSON.stringify(isTestNet ? env.TESTNET_WEB3X : env.MAINNET_WEB3X),
                CHAIN_ID: JSON.stringify(isTestNet ? env.TESTNET_CHAIN_ID : env.MAINNET_CHAIN_ID),
                CHAIN_NAME: JSON.stringify(isTestNet ? env.TESTNET_CHAIN_NAME : env.MAINNET_CHAIN_NAME),
                BSCSCAN: JSON.stringify(isTestNet ? env.TESTNET_BSCSCAN : env.MAINNET_BSCSCAN),
            }
        }),
    ],
}));

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
