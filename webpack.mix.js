const mix = require('laravel-mix');
const WebpackObfuscator = require('webpack-obfuscator');

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
const obfuscator = require('./resources/js/utils/obfuscator');

const plugins = [];
if (env.PROD !== undefined) {
	plugins.push(new WebpackObfuscator(obfuscator.low));
}

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
				CONTRACT_SYMBOL: JSON.stringify(isTestNet ? env.TESTNET_CONTRACT_SYMBOL : env.MAINNET_CONTRACT_SYMBOL),
				CONTRACT_ADDRESS: JSON.stringify(isTestNet ? env.TESTNET_CONTRACT_ADDRESS : env.MAINNET_CONTRACT_ADDRESS),
				CONTRACT_DECIMALS: JSON.stringify(isTestNet ? env.TESTNET_CONTRACT_DECIMALS : env.MAINNET_CONTRACT_DECIMALS),
				WALLET_PAGOS: JSON.stringify(isTestNet ? env.TESTNET_WALLET_PAGOS : env.MAINNET_WALLET_PAGOS),
				PROD: !isTestNet
			}
		}),
		...plugins,
	],
}));

mix.js('resources/js/game.js', 'public/assets/js')
    .postCss('resources/css/game.css', 'public/assets/css', [
        //
    ]);
