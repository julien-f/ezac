'use strict';

//====================================================================

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

// Static file serving.
var ecstatic = require('ecstatic');

// File system based noSQL database.
var fdb = require('final-db');

// File system with promises.
var fs = require('final-fs');

// Options parsing.
var optimist = require('optimist');

// HTTP server with REST facilities.
var restify = require('restify');

//====================================================================

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
			p: {
				alias: 'port',
				check: function (value) {
					return (value > 0) && (value < 65536);
				},
				default: 80,
				describe: 'port to use',
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
	], function (name) {
		var collection = new fdb.Collection([__dirname, 'database', name]);

		// Effectively creates the collection.
		collection.flush();

		db[name] = collection;
	});

	// Creates the HTTP/REST server.
	var server = restify.createServer({
		// certificate: yield fs.readFile([__dirname, 'certificate.pem']),
		// key: yield fs.readFile([__dirname, 'key.pem']),
	});
	server.listen(options.port);

	server

		// Checks whether the server support a content type acceptable for
		// the client.
		.use(restify.acceptParser(server.acceptable))

		// TODO: Authentication.
		// .use(restify.authorizationParser())

		// Checks for clock skew: used by throttle?
		// .use(restify.dateParser())


		// Parses the query string: used by jsonp.
		// .use(restify.queryParser({
		// 	mapParams: false,
		// }))

		// Adds support for JSONP, not used for now.
		// .use(restify.jsonp())

		// Compresses the response if possible.
		.use(restify.gzipResponse())

		// Parses the body (forms, JSON, etc.).
		.use(restify.bodyParser())

		// Protects the server against DOS.
		.use(restify.throttle({
			burst: 100,
			rate: 50,
			ip: true,
		}))

		// TODO: Handles last-modified, ETags, etc.
		// .use(restify.conditionalRequest())
	;

	each(db, function (collection, name) {
		// Gets all items in the collection.
		server.get('/'+ name, function (req, res) {
			collection.find().then(function (records) {
				res.send(records);
			});
		});

		// Gets one item from the collection.
		server.get('/'+ name +'/:id', function (req, res, next) {
			collection.find(req.params.id).then(
				function (record) {
					res.send(record);
				},
				function () {
					next(new Error(req.url));
				}
			);
		});

		// Adds one item to the collection.
		server.post('/'+ name, function (req, res) {
			collection.insert(req.body);
			res.send(req.body.id);

			collection.flush();
		});

		// Replaces (updates) one item in the collection.
		server.put('/'+ name +'/:id', function (req, res) {
			req.body.id = req.params.id;
			collection.update(req.body);
			res.send(true);

			collection.flush();
		});

		// Deletes one item from the collection.
		server.del('/'+ name +'/:id', function (req, res) {
			collection.remove(req.params.id);
			res.send(true);

			collection.flush();
		});
	});

	// Serves static files (i.e. the client).
	server.get(/.*/, ecstatic({
		root: __dirname +'/../client/dist/',
	}));
})();
