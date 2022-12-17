module.exports = function(grunt) {

	// Load task libraries
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	
	// Register tasks
	grunt.registerTask('default', ['clean', 'compass', 'uglify']);
	
	// Configure registered tasks
	grunt.initConfig({
		
		// Remove assets before building
		clean: {
			build: {
				src: [
					'assets/css/*.css',
					'assets/js/*.js',
					'assets/js/*.js.min',
					
					// Don't remove the source JS files
					'!assets/js/SwiftypeComponents.js',
					'!assets/js/configuration.js',
					'!assets/js/swiftypecomponents-ie.js'
				]
			}
		},
	
		// Compile SCSS
		// @todo Add script to run for local dev, i.e. uncompressed
		// @todo At the time of writing this; compass was unable to properly create map files
		compass: {
		  dist: {
			options: {
			  sassDir: 'assets/scss',
			  cssDir: 'assets/css',
			  environment: 'production',
			  outputStyle: 'compressed'
			}
		  }
		},
	
		// Minify JS
		uglify: {
			options: {
				mangle: false
			},
			assets: {
				files: {
					'assets/js/SwiftypeComponents.min.js': ['assets/js/SwiftypeComponents.js'],
					'assets/js/configuration.min.js': ['assets/js/configuration.js'],
					'assets/js/swiftypecomponents-ie.min.js': ['assets/js/swiftypecomponents-ie.js']
				}
			}
		}
	});
};