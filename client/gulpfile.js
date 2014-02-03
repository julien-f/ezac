'use strict';

//====================================================================

var bower = require('bower');
var browserify = require('gulp-browserify');
var clean = require('gulp-clean');
var csso = require('gulp-csso');
var embedlr = require('gulp-embedlr');
var gulp = require('gulp');
var jade = require('gulp-jade');
var less = require('gulp-less');
var livereload = require('gulp-livereload');
var mocha = require('gulp-mocha');
var tinylr = require('tiny-lr');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');

//====================================================================

var LIVERELOAD_PORT = 46417;
var BOWER_DIR = __dirname +'/bower_components';
var DIST_DIR = __dirname +'/dist';
var SRC_DIR = __dirname +'/app';

var watchChanges = true;

var src = function (pattern) {
	var i, n;

	if (pattern instanceof Array)
	{
		for (i = 0, n = pattern.length; i < n; ++i)
		{
			pattern[i] = SRC_DIR +'/'+ pattern[i];
		}
	}
	else
	{
		pattern = SRC_DIR +'/'+ pattern;
	}

	var stream = gulp.src(pattern, {
		base: SRC_DIR,
	});

	if (watchChanges)
	{
		stream = stream
			.pipe(watch());
	}

	return stream;
};

(function() {
	if (!watchChanges)
	{
		return;
	}

	// Creates the livereload server.
	var server = tinylr();
	server.listen(LIVERELOAD_PORT);

	// Binds it to the gulp plugin.
	livereload = livereload.bind(null, server);

	// Binds the port to the embedlr plugin.
	embedlr = embedlr.bind(null, {
		port: LIVERELOAD_PORT,
	})
})();

//====================================================================

gulp.task('build-pages', function () {
	return src('index.jade')
		.pipe(jade())
		.pipe(embedlr())
		.pipe(gulp.dest(DIST_DIR))
		.pipe(livereload())
});

gulp.task('build-scripts', ['install-bower-components'], function () {
	return src('app.js')
		.pipe(browserify({
			debug: !gulp.env.production,
			transform: [
				'browserify-plain-jade',
				'debowerify',
				'deamdify',
			],
		}))
		.pipe(uglify({
			outSourceMaps: !gulp.env.production,
		}))
		.pipe(gulp.dest(DIST_DIR))
		.pipe(livereload());
});

gulp.task('build-styles', ['install-bower-components'], function () {
	return src('app.less')
		.pipe(less({
			paths: [
				BOWER_DIR +'/font-awesome/less',
				BOWER_DIR +'/strapless/less',
			],
		}))
		.pipe(csso())
		.pipe(gulp.dest(DIST_DIR))
		.pipe(livereload());
});

gulp.task('copy-assets', ['install-bower-components'], function () {
	return src('assets/**/*')
		.pipe(gulp.dest(DIST_DIR))
		.pipe(livereload());
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
	return src('**/*.spec.js')
		.pipe(mocha({
			reporter: 'spec'
		}));
});

//------------------------------------------------------------------------------

gulp.task('default', ['build']);
