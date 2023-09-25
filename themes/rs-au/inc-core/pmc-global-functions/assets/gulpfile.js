/**
 * Gulp task file containing all Gulp tasks for asset pipeline
 *
 * @since 2019-04-15 Amit Gupta
 */

"use strict";

const gulp    = require( 'gulp' );
const babel   = require( 'gulp-babel' );
const plumber = require( 'gulp-plumber' );
const watch   = require( 'gulp-watch' );
const uglify  = require( 'gulp-uglify' );
const rename  = require( 'gulp-rename' );
const notify  = require( 'gulp-notify' );
const cached  = require( 'gulp-cached' );
const del     = require( 'del' );

var jsConfig = {
	src: 'src/js/**/*.js',
	destination: 'build/js'
};

function drawLogEvent( typeEvent, text ) {

	var date = new Date();
	var time = (date.getHours() < 10 ? '0' : '') + date.getHours() + ':';
	time    += (date.getMinutes() < 10 ? '0' : '') + date.getMinutes() + ':';
	time    += (date.getSeconds() < 10 ? '0' : '') + date.getSeconds();

	console.log( '[' + time + '] \x1b[35m' + typeEvent + '\x1b[0m: \x1b[32m' + text + '\x1b[0m' );

}

function taskCleanArtifacts() {
	return del( [ './build/' ] );
}

function taskBuildJs() {

	return (
		gulp
			.src( [ jsConfig.src ] )
			.pipe( plumber() )
			.pipe( babel() )
			.pipe( uglify() )
			.pipe( notify( 'JS minimized <%= file.relative %>' ) )
			.pipe( gulp.dest( jsConfig.destination ) )
	);

}

function taskWatchFiles() {

	var watcherJS = gulp.watch( jsConfig.src, gulp.series( taskBuildJs ) );

	watcherJS.on( 'change', function ( event ) {
		drawLogEvent( 'CHANGED JS', event.path );
	} );

}


/*
 * Gulp tasks
 */
const buildJs    = gulp.series( taskBuildJs );
const build      = gulp.series( taskCleanArtifacts, gulp.parallel( buildJs ) );
const watchFiles = gulp.parallel( taskWatchFiles );

/*
 * Export tasks
 */
exports.buildJs = buildJs;
exports.clean   = taskCleanArtifacts;
exports.build   = build;
exports.watch   = watchFiles;
exports.default = build;

//EOF
