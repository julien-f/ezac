'use strict';

//==============================================================================

var each;
(function () {
	var lodash = require('lodash');
	each = lodash.each;
})();

// Helpers to deal with promises with generators.
var coroutine, spawn;
(function () {
	var bluebird = require('bluebird');

	coroutine = bluebird.coroutine;
	spawn = bluebird.spawn;
})();

// File system based noSQL database.
var fdb = require('final-db');

// Options parsing.
var optimist = require('optimist');

//==============================================================================

coroutine(function *main() {
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
	var db = {
		bookings: new fdb.Collection([__dirname, 'database', 'bookings']),
		events: new fdb.Collection([__dirname, 'database', 'events']),
		offers: new fdb.Collection([__dirname, 'database', 'offers']),
		users: new fdb.Collection([__dirname, 'database', 'users']),
	};

	// Automatically flushes the database when exiting.
	process.on('exit', function () {
		each(db, function (collection) {
			collection.flush();
		});
	});

	// TODO:
	// - starts the REST server.
})();
