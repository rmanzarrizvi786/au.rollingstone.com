'use strict';

// Webpack dependencies
var path = require('path');
var webpack = require('webpack');

// Variables
const isProduction = process.argv.indexOf('-p') !== -1;
const isDevelopment = !isProduction;
const ROOT_DIR = path.resolve(__dirname);
const CONFIG_DIR = path.resolve(__dirname, 'config');
const SRC_DIR = path.resolve(__dirname, 'src');
const BUILD_DIR = path.resolve(__dirname, 'build');

/**
 * Uglify JS Plugin.
 */
const UglifyJS = require('uglify-js');

/**
 * Clean webpack plugin, To cleanup directory.
 * i.g. build/
 *
 * Reference : https://github.com/johnagan/clean-webpack-plugin
 *
 * @type Module clean-webpack-plugin|Module clean-webpack-plugin
 */
const CleanWebpackPlugin = require('clean-webpack-plugin');

const pathsToClean = [
	'build'
];


/**
 * Text Extractor plugin For css
 * Reference : https://www.npmjs.com/package/extract-text-webpack-plugin
 *
 * @type Module extract-text-webpack-plugin|Module extract-text-webpack-plugin
 */
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const extractSass = new ExtractTextPlugin({
	filename: 'css/[name].css'
});

/**
 * Stylelint Plugin.
 *
 * Reference : https://stylelint.io/user-guide/configuration/
 *
 * @type Module stylelint-webpack-plugin|Module stylelint-webpack-plugin
 */
const styleLintPlugin = require( 'stylelint-webpack-plugin' );
const styleLintOptions = {
	'extends': 'stylelint-config-wordpress/scss',
	'rules': {
		'declaration-property-unit-whitelist': {
			'line-height': [ 'em', 'rem' ]
		},
		'rule-empty-line-before': null,
		'at-rule-empty-line-before': null,
		'no-missing-end-of-source-newline': null,
		'selector-list-comma-newline-after': null
	}
};

/**
 * Clone plugin.
 *
 * @type Module copy-webpack-plugin|Module copy-webpack-plugin
 */
const copyWebpackPlugin = require('copy-webpack-plugin');
const copyWebpackConfig = [
	{
		context: 'src/fonts/',
		from: '**/*',
		to: BUILD_DIR + '/fonts'
	},
	{
		context: 'src/images/',
		from: '**/*',
		to: BUILD_DIR + '/images'
	},
	{
		context: 'src/js/vendor',
		from: '**/*',
		to: BUILD_DIR + '/js/vendor',
		transform: function(content, path) {
			const result = UglifyJS.minify(content.toString());
			return Buffer.from( result.code );
		}
 	},
	{
		context: 'src/js/pmc-film-review',
		from: '**/*',
		to: BUILD_DIR + '/js/pmc-film-review',
		transform: function(content, path) {
			const result = UglifyJS.minify(content.toString());
			return Buffer.from( result.code );
		}
 	}
	// @TODO : Add For Premier.
];

/**
 * Image optimization plugin.
 */
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const ImageminOption = {
	test: 'images/**',
	disable: isDevelopment, // Disable during development
	pngquant: {
		quality: '95-100'
	}
};

//=========================================================
//  Entry
//---------------------------------------------------------
const entry = {
	'404': CONFIG_DIR + '/404.js',
	amp: CONFIG_DIR + '/amp.js',
	'amp-country': CONFIG_DIR + '/amp-country.js',
	archive: CONFIG_DIR + '/archive.js',
	'country': CONFIG_DIR + '/country.js',
	'featured-article': CONFIG_DIR + '/featured-article.js',
	gallery: CONFIG_DIR + '/gallery.js',
	home: CONFIG_DIR + '/home.js',
	list: CONFIG_DIR + '/list.js',
	main: CONFIG_DIR + '/main.js',
	results: CONFIG_DIR + '/results.js',
	'section-front': CONFIG_DIR + '/section-front.js',
	single: CONFIG_DIR + '/single.js',
	'standard-template': CONFIG_DIR + '/standard-template.js',
	'video-article': CONFIG_DIR + '/video-article.js',
	'video-landing': CONFIG_DIR + '/video-landing.js',
	'video-tag': CONFIG_DIR + '/video-tag.js',
	'rspro': CONFIG_DIR + '/rspro.js',
	'rs-live-media': CONFIG_DIR + '/rs-live-media.js'
};

//=========================================================
//  Rules
//---------------------------------------------------------
const rules = {

	// Javascript loaders
	pre: {
		enforce: 'pre',
		test: /\.js$/,
		exclude: /(node_modules|nobundle|vendor)/,
		use: {
			loader: 'eslint-loader',
			options: {
				configFile: '.eslintrc.json'
			}
		}
	},
	/**
	 * JS Loader
	 */
	js: {
		test: /\.js$/,
		include: SRC_DIR,
		exclude: /(node_modules|nobundle|vendor)/,
		loader: 'babel-loader',
		query: {
			presets: ['es2015']
		}
	},
	// Reference : https://github.com/webpack-contrib/file-loader
	// DO NOT ADD .svg file-loader wil currept it.
	images: {
		test: /\.(png|jpe?g|gif|ico)$/,
		loader: {
			loader: 'file-loader',
			options: {
				name: function (path) {
					return path.replace(SRC_DIR + '/', '');
				},
				publicPath: '../'
			}
		}
	},
	fonts: {
		test: /\.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
		loader: {
			loader: 'file-loader',
			options: {
				name: function (path) {
					return path.replace(SRC_DIR + '/', '');
				},
				publicPath: '../'
			}
		}
	},
	json: {
		test: /\.json$/,
		loader: 'json-loader'
	},
	scss: {
		test: /\.scss$/,
		use: extractSass.extract({
			use: [
				{
					loader: 'css-loader',
					options: {
						importLoaders: 1
					}
				},
				{
					loader: 'postcss-loader'
				},
				{
					loader: 'sass-loader',
					options: {
						includePaths: [
							SRC_DIR,
							'./node_modules/scss-query/query/'
						]
					}
				},
			],
			// Use style-loader in development
			fallback: 'style-loader'
		})
	},
};

//=========================================================
//  Plugins
//---------------------------------------------------------


const UglifyOptions = {
	parallel: true,
	compress: {
		warnings: true
	},
	output: {
		comments: false,
	}
};

const plugins = {
	cleanup: new CleanWebpackPlugin( pathsToClean ),
	stylelint: new styleLintPlugin( styleLintOptions ),
	copy: new copyWebpackPlugin( copyWebpackConfig ),
	scss: extractSass,
	uglifyjs: new webpack.optimize.UglifyJsPlugin( UglifyOptions ),
	minify: new webpack.LoaderOptionsPlugin( { minimize: true } ),
	imagemin: new ImageminPlugin( ImageminOption )
};

//=========================================================
//  CONFIG
//---------------------------------------------------------
module.exports = {
	entry: entry,
	output: {
		path: BUILD_DIR + '/',
		filename: 'js/[name].js'
	},
	module: {
		rules: [
			rules.js,
			rules.scss,
			rules.images,
			rules.fonts,
			rules.json
		]
	},
	plugins: [
		plugins.scss
	],
	watch: isDevelopment,
	watchOptions: {
		ignored: /node_modules/
	}
};

if ( isProduction ) {

	module.exports.module.rules = ( module.exports.module.rules || [] ).concat( [ rules.pre ] );

	module.exports.plugins = (module.exports.plugins || []).concat([
		plugins.cleanup,
		plugins.stylelint,
		plugins.copy,
		plugins.uglifyjs,
		plugins.minify,
		plugins.imagemin,
	]);
}
