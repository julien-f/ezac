'use strict';

//==============================================================================

var bower = require('bower');
var browserify = require('gulp-browserify');
var clean = require('gulp-clean');
var csso = require('gulp-csso');
var gulp = require('gulp');
var jade = require('gulp-jade');
var less = require('gulp-less');
var mocha = require('gulp-mocha');
var uglify = require('gulp-uglify');

//==============================================================================

var BOWER_DIR = __dirname +'/bower_components';
var DIST_DIR = __dirname +'/dist';
var SRC_DIR = __dirname +'/app';

//==============================================================================

gulp.task('build-pages', function () {
	gulp.src(SRC_DIR +'/index.jade')
		.pipe(jade())
		.pipe(gulp.dest(DIST_DIR));
});

gulp.task('build-scripts', ['install-bower-components'], function () {
	gulp.src(SRC_DIR +'/app.js')
		.pipe(browserify({
			debug: !gulp.env.production,
		}))
		.pipe(uglify({
			outSourceMaps: !gulp.env.production,
		}))
		.pipe(gulp.dest(DIST_DIR));
});

gulp.task('build-styles', ['install-bower-components'], function () {
	gulp.src(SRC_DIR +'/app.less')
		.pipe(less({
			paths: [
				BOWER_DIR +'/strapless/less',
			],
		}))
		.pipe(csso())
		.pipe(gulp.dest(DIST_DIR));
});

gulp.task('install-bower-components', function (done) {
	bower.commands.install()
		.on('error', done)
		.on('end', function () {
			done();
		});
});

//------------------------------------------------------------------------------

gulp.task('clean', function () {
	gulp.src([
		BOWER_DIR,
		DIST_DIR,
	], {
		read: false,
	})
		.pipe(clean());
});

gulp.task('test', function () {
	gulp.src(SRC_DIR +'/**/*.spec.js')
		.pipe(mocha({
			reporter: 'spec'
		}));
});

//------------------------------------------------------------------------------

gulp.task('default', [
	'build-pages',
	'build-scripts',
	'build-styles',
]);
