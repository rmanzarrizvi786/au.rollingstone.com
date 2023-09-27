module.exports = {
	syntax: 'postcss-scss',
	plugins: {
		'postcss-import': {},
		'postcss-cssnext': {
			warnForDuplicates: false
		},
		'autoprefixer': {
			grid: true,
			browsers: [
				'Edge >= 13',
				'Explorer >= 11',
				'ExplorerMobile >= 11',
				'Firefox >= 50',
				'Chrome >= 55',
				'ChromeAndroid >= 55',
				'Android >= 55',
				'Safari >= 10',
				'Opera >= 42',
				'iOS >= 9'
			]
		},
		'postcss-pxtorem': {
			rootValue: 16,
			unitPrecision: 5,
			propList: ['*'],
			selectorBlackList: [],
			replace: true,
			mediaQuery: true,
			minPixelValue: 2
		},
		'postcss-assets': {},
	}
}
