'use strict';

//====================================================================

var gulp = require('gulp');

//====================================================================

var options = require('minimist')(process.argv, {
	boolean: ['production'],

	default: {
		production: false,
	}
});

//====================================================================

var DIST_DIR = __dirname +'/dist';
var SRC_DIR = __dirname +'/app';

// Bower directory is read from its configuration.
var BOWER_DIR = (function () {
  var cfg;

  try
  {
    cfg = JSON.parse(require('fs').readFileSync('./.bowerrc'));
  }
  catch (error)
  {
    cfg = {};
  }

  cfg.cwd || (cfg.cwd = __dirname);
  cfg.directory || (cfg.directory = 'bower_components');

  return cfg.cwd +'/'+ cfg.directory;
})();

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

	// Requires dependencies only when necessary (and only once).
	return function () {
		// gulp-plumber prevents streams from disconnecting when errors.
		// See: https://gist.github.com/floatdrop/8269868#file-thoughts-md
		var plumber = require('gulp-plumber');

		var watch = require('gulp-watch');

		src = function (pattern) {
			return watch({
				glob: SRC_DIR +'/'+ pattern,
				base: SRC_DIR,
			}).pipe(plumber({
				errorHandler: console.error,
			}));
		};

		return src.apply(this, arguments);
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
		.pipe(require('gulp-jade')())
	;

	if (!PRODUCTION)
	{
		stream = stream.pipe(require('gulp-embedlr')({
			port: LIVERELOAD,
		}));
	}

	return stream.pipe(dest());
});

// TODO: Use watchify (https://github.com/substack/watchify).
gulp.task('build-scripts', ['install-bower-components'], function () {
	var stream = src('app.js')
		.pipe(require('gulp-browserify')({
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
		stream = stream.pipe(require('gulp-uglify')());
	}

	return stream.pipe(dest());
});

gulp.task('build-styles', ['install-bower-components'], function () {
	var stream = src('app.less')
		.pipe(require('gulp-less')({
			paths: [
				BOWER_DIR +'/font-awesome/less',
				BOWER_DIR +'/strapless/less',
			],
		}))
	;

	if (PRODUCTION)
	{
		stream = stream.pipe(require('gulp-csso')());
	}

	return stream.pipe(dest());
});

gulp.task('copy-assets', ['install-bower-components'], function () {
	return src('assets/**/*')
		.pipe(dest());
});

gulp.task('install-bower-components', function (done) {
	var bower = require('bower');

	bower.config.cwd = __dirname;
	bower.config.directory = require('path').relative(__dirname, BOWER_DIR);

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

gulp.task('check-scripts', function () {
	var jshint = require('gulp-jshint');

	return gulp.src(SRC_DIR +'/**/*.js')
		.pipe(require('gulp-jsvalidate')())
		.pipe(jshint())
		.pipe(jshint.reporter('jshint-stylish'))
	;
});

gulp.task('clean', function () {
	return gulp.src(DIST_DIR, {
		read: false,
	}).pipe(require('gulp-clean')());
});

gulp.task('distclean', ['clean'], function () {
	return gulp.src(BOWER_DIR, {
		read: false,
	}).pipe(require('gulp-clean')());
});

gulp.task('test', function () {
	return gulp.src(SRC_DIR +'/**/*.spec.js')
		.pipe(require('gulp-mocha')({
			reporter: 'spec'
		}));
});

//------------------------------------------------------------------------------

gulp.task('default', ['build']);
