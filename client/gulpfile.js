'use strict';

//====================================================================

var bower = require('bower');
var gulp = require('gulp');

var browserify = require('gulp-browserify');
var clean = require('gulp-clean');
var csso = require('gulp-csso');
var embedlr = require('gulp-embedlr');
var jade = require('gulp-jade');
var less = require('gulp-less');
var mocha = require('gulp-mocha');
var uglify = require('gulp-uglify');

//====================================================================

var options = require('minimist')(process.argv, {
	boolean: ['production'],

	default: {
		production: false,
	}
});

//====================================================================

var BOWER_DIR = __dirname +'/bower_components';
var DIST_DIR = __dirname +'/dist';
var SRC_DIR = __dirname +'/app';

var PRODUCTION = options.production;
var LIVERELOAD = 46417;

//--------------------------------------------------------------------

var src = (function () {
	if (PRODUCTION)
	{
		return function (pattern) {
			return gulp.src(SRC_DIR +'/'+ pattern, {
				base: SRC_DIR,
			});
		};
	}

	var watch = require('gulp-watch');

	return function (pattern) {
		return gulp.src(SRC_DIR +'/'+ pattern, {
			base: SRC_DIR,
		}).pipe(watch());
	};
})();

var dest = (function () {
	if (PRODUCTION)
	{
		return function () {
			return gulp.dest(DIST_DIR);
		};
	}

	// Creates the server only when necessary (and only once).
	return function () {
		var lrserver = require('tiny-lr')();
		lrserver.listen(LIVERELOAD);

		var livereload = require('gulp-livereload');

		var combine = require('stream-combiner');

		dest = function () {
			return combine(
				gulp.dest(DIST_DIR),
				livereload(lrserver)
			);
		};

		return dest();
	};
})();

//====================================================================

gulp.task('build-pages', function () {
	var stream = src('index.jade')
		.pipe(jade())
	;

	if (!PRODUCTION)
	{
		stream = stream.pipe(embedlr({
			port: LIVERELOAD,
		}));
	}

	return stream.pipe(dest());
});

gulp.task('build-scripts', ['install-bower-components'], function () {
	var stream = src('app.js')
		.pipe(browserify({
			debug: !PRODUCTION,
			transform: [
				'browserify-plain-jade',
				'debowerify',
				'deamdify',
			],
		}))
	;

	if (PRODUCTION)
	{
		stream = stream.pipe(uglify());
	}

	return stream.pipe(dest());
});

gulp.task('build-styles', ['install-bower-components'], function () {
	var stream = src('app.less')
		.pipe(less({
			paths: [
				BOWER_DIR +'/font-awesome/less',
				BOWER_DIR +'/strapless/less',
			],
		}))
	;

	if (PRODUCTION)
	{
		stream = stream.pipe(csso());
	}

	return stream.pipe(dest());
});

gulp.task('copy-assets', ['install-bower-components'], function () {
	return src('assets/**/*')
		.pipe(dest());
});

gulp.task('install-bower-components', function (done) {
	bower.commands.install()
		.on('error', done)
		.on('end', function () {
			done();
		});
});

//--------------------------------------------------------------------

gulp.task('build', [
	'build-pages',
	'build-scripts',
	'build-styles',
	'copy-assets',
]);

gulp.task('clean', function () {
	return gulp.src([
		BOWER_DIR,
		DIST_DIR,
	], {
		read: false,
	})
		.pipe(clean());
});

gulp.task('test', function () {
	return gulp.src(SRC_DIR +'/**/*.spec.js')
		.pipe(mocha({
			reporter: 'spec'
		}));
});

//------------------------------------------------------------------------------

gulp.task('default', ['build']);
