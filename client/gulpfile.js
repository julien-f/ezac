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
var LIVERELOAD = 35827;

//--------------------------------------------------------------------

// Browserify plugin for gulp.js which uses watchify in development
// mode.
var browserify = function (path, opts) {
	var bundler = require(PRODUCTION ? 'browserify' : 'watchify')(path);
	var file = new (require('vinyl'))({
		base: opts.base,
		path: require('path').resolve(path),
	});
	var stream = new require('stream').Readable({
		objectMode: true,
	});

	if (opts.transform)
	{
		[].concat(opts.transform).forEach(function (transform) {
			bundler.transform(transform);
		});
	}

	var bundle = bundler.bundle.bind(bundler, function (error, bundle) {
		if (error)
		{
			console.warn(error);
			return;
		}

		file.contents = new Buffer(bundle);
		stream.push(file);

		// EOF is sent only in production.
		if (PRODUCTION)
		{
			stream.push(null);
		}
	});

	stream._read = function () {
		// Ignore subsequent reads.
		stream._read = function () {};

		// Register for updates (does nothing if we are not using
		// Browserify, in production).
		bundler.on('update', bundle);

		bundle();
	};
	return stream;
};

var pipe = function () {
	pipe = require('event-stream').pipe;
	return pipe.apply(this, arguments);
};

var concat = function () {
	concat = require('event-stream').concat;
	return concat.apply(this, arguments);
};

var noop = function () {
	var PassThrough = require('stream').PassThrough;

	noop = function () {
		return new PassThrough({
			objectMode: true
		});
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
		return pipe(
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

gulp.task('build-scripts', ['install-bower-components'], function () {
	return browserify(SRC_DIR +'/app.js', {
		base: SRC_DIR,
		debug: !PRODUCTION,
		transform: [
			'browserify-plain-jade',
			'debowerify',
			'deamdify',
		],
	})
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
