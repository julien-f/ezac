'use strict';

//==============================================================================

var createHttpServer = require('http').createServer;

//------------------------------------------------------------------------------

var connect = require('connect');

// Helpers to deal with promises with generators.
var coroutine, spawn;
(function () {
	var bluebird = require('bluebird');

	coroutine = bluebird.coroutine;
	spawn = bluebird.spawn;
})();

var each;
(function () {
	var lodash = require('lodash');
	each = lodash.each;
})();

// File system based noSQL database.
var fdb = require('final-db');

// Options parsing.
var optimist = require('optimist');

//==============================================================================

(function main() {
	var options = optimist
		.usage('Usage: $0 [<option>...]')
		.options({
			h: {
				alias: 'help',
				boolean: true,
				describe: 'display this help message',
			},
			v: {
				alias: 'version',
				boolean: true,
				describe: 'display the version number',
			},
		})
		.argv;

	if (options.help)
	{
		optimist.showHelp();
		return;
	}

	if (options.version)
	{
		var pkg = require(__dirname +'/package');
		console.log('Ezac version '+ pkg.version);
		return;
	}

	// Opens the database.
	var db = Object.create(null);
	each([
		'bookings',
		'events',
		'offers',
		'users',
	], function (collection) {
		db[collection] = new fdb.Collection([__dirname, 'database', collection]);
	});

	// Automatically flushes the database when exiting.
	process.on('exit', function () {
		each(db, function (collection) {
			collection.flush();
		});
	});

	// Creates the web-server.
	var webserver = createHttpServer().listen(8080);

	// Serves static files.
	webserver.on(
		'request',
		connect()
			.use('/', connect.static(__dirname +'/../client/dist'))
	);

	// TODO:
	// - starts the REST server.
})();
