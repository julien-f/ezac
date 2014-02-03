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

	// Creates the HTTP/REST server.
	var server = restify.createServer({
		// certificate: yield fs.readFile([__dirname, 'certificate.pem']),
		// key: yield fs.readFile([__dirname, 'key.pem']),
	});
	server.listen(8080);

	server

		// Checks whether the server support a content type acceptable for
		// the client.
		.use(restify.acceptParser(server.acceptable))

		// TODO: Authentication.
		// .use(restify.authorizationParser())

		// Checks for clock skew: used by throttle?
		// .use(restify.dateParser())


		// Parses the query string: used by jsonp.
		.use(restify.queryParser({
			mapParams: false,
		}))

		// Adds support for JSONP.
		.use(restify.jsonp())

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


	// Serves static files (i.e. the client).
	server.get(/.*/, restify.serveStatic({
		default: 'index.html',

		// FIXME: Unix paths.
		directory: __dirname +'/../client/dist/',
	}));

	each(db, function (collection, name) {
		server.get('/'+ name, function (req, res) {
			collection.find().then(function (records) {
				res.send(records);
			});
		});

		server.get('/'+ name +'/:id', function (req, res) {
			collection.find(req.params.id).then(function (records) {
				res.send(records);
			});
		});

		server.put('/'+ name +'/new', function (req, res) {
			collection.insert(req.body).then(console.log);
			// function (record) {
			// 	res.send({id: record.id});
			// });
		});

		server.post('/'+ name +'/:id', function (req, res) {
			collection.update(req.body).then(console.log);
			// .then(function (result) {

			// });
		});

		server.del('/'+ name +'/:id', function (req, res) {
			collection.remove(req.params.id).then(console.log);
		});
	});
})();
