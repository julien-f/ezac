'use strict';

//====================================================================

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

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

// var browserify = function () {
// 	if (PRODUCTION)
// 	{
// 		browserify = require('gulp-browserify');
// 		return browserify.apply(this, arguments);
// 	}

// 	browfunction (options) {
// 	var watchify = new require('watchify')();

// 	return require('through2').obj(function (file, enc, done) {

// 	});
// };

var combine = function () {
	combine = require('stream-combiner');
	return combine.apply(this, arguments);
};

var concat = function () {
	concat = require('event-stream').concat;
	return concat.apply(this, arguments);
};

var noop = function () {
	var stream = new require('stream').PassThrough({
		objectMode: true
	});

	noop = function () {
		return stream;
	};

	return noop.apply(this, arguments);
};

var gIf = function (cond, then, otherwise) {
	return cond ? then : otherwise || noop();
};

var src = (function () {
	if (PRODUCTION)
	{
		return function (pattern) {
			return gulp.src(SRC_DIR +'/'+ pattern, {
				base: SRC_DIR,
			});
		};
	}

	// gulp-plumber prevents streams from disconnecting when errors.
	// See: https://gist.github.com/floatdrop/8269868#file-thoughts-md
	return function (pattern) {
		return $.watch({
			glob: SRC_DIR +'/'+ pattern,
			base: SRC_DIR,
		}).pipe($.plumber({
			errorHandler: console.error,
		}));
	};
})();

var dest = (function () {
	if (PRODUCTION)
	{
		return function () {
			return gulp.dest(DIST_DIR);
		};
	}

	return function () {
		return combine(
			gulp.dest(DIST_DIR),
			$.livereload(LIVERELOAD)
		);
	};
})();

//====================================================================

gulp.task('build-pages', function () {
	return src('index.jade')
		.pipe($.jade())
		.pipe(gIf(!PRODUCTION, $.embedlr({
			port: LIVERELOAD,
		})))
		.pipe(dest())
	;
});

// TODO: Use watchify (https://github.com/substack/watchify).
gulp.task('build-scripts', ['install-bower-components'], function () {
	return src('app.js')
		.pipe($.browserify({
			debug: !PRODUCTION,
			transform: [
				'browserify-plain-jade',
				'debowerify',
				'deamdify',
			],
		}))
		.pipe(gIf(PRODUCTION, $.uglify()))
		.pipe(dest())
	;
});

gulp.task('build-styles', ['install-bower-components'], function () {
	return src('app.less')
		.pipe($.less({
			paths: [
				BOWER_DIR +'/font-awesome/less',
				BOWER_DIR +'/strapless/less',
			],
		}))
		.pipe(gIf(PRODUCTION, $.csso()))
		.pipe(dest())
	;
});

gulp.task('copy-assets', ['install-bower-components'], function () {
	return src('assets/**/*')
		.pipe(dest())
	;
});

gulp.task('install-bower-components', function (done) {
	require('bower').commands.install()
		.on('error', done)
		.on('end', function () {
			done();
		})
	;
});

//--------------------------------------------------------------------

gulp.task('build', [
	'build-pages',
	'build-scripts',
	'build-styles',
	'copy-assets',
]);

gulp.task('check-scripts', function () {
	return gulp.src(SRC_DIR +'/**/*.js')
		.pipe($.jsvalidate())
		.pipe($.jshint())
		.pipe($.jshint.reporter('jshint-stylish'))
	;
});

gulp.task('clean', function () {
	return gulp.src(DIST_DIR, {
		read: false,
	}).pipe($.clean());
});

gulp.task('distclean', ['clean'], function () {
	return gulp.src(BOWER_DIR, {
		read: false,
	}).pipe($.clean());
});

gulp.task('test', function () {
	return gulp.src(SRC_DIR +'/**/*.spec.js')
		.pipe($.mocha({
			reporter: 'spec'
		}))
	;
});

//------------------------------------------------------------------------------

gulp.task('default', ['build']);
