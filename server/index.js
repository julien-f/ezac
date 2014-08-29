#!/bin/sh
':' //; exec node --harmony "$0" "$@"

'use strict';

//====================================================================

var coroutine = require('bluebird').coroutine;
var ecstatic = require('ecstatic');
var eventToPromise = require('event-to-promise');
var fdb = require('final-db');
var forEach = require('lodash.foreach');
var restify = require('restify');
var yargs = require('yargs');

//====================================================================

exports = module.exports = coroutine(function *main() {
	var options = yargs
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
				default: 80,
				describe: 'port to use',
			},
		})
		.check(function (options) {
			if (options.help)
			{
				throw '';
			}

			var port = options.port;
			if (!((0 <= port) && (port < 65536) && (0 === port % 1)))
			{
				throw '--port should be an integer between 0 and 65535';
			}
		})
		.argv
	;

	if (options.version)
	{
		var pkg = require(__dirname +'/package');
		return 'Ezac version '+ pkg.version;
	}

	// Opens the database.
	var db = Object.create(null);
	forEach([
		'bookings',
		'events',
		'offers',
		'users',
	], function (name) {
		db[name] = new fdb.Collection([__dirname, 'database', name]);
	});

	// Creates the HTTP/REST server.
	var server = restify.createServer({
		name: 'Ezac',
		version: '1.0.0',

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
		.use(restify.dateParser())

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

		// Handles last-modified, ETags, etc.
		.use(restify.conditionalRequest())
	;

	forEach(db, function (collection, name) {
		// Gets all items in the collection.
		server.get('/'+ name, function (req, res, next) {
			collection.find().then(function (records) {
				res.send(records);
				next();
			});
		});

		// Gets one item from the collection (TODO: implements HEAD).
		server.get('/'+ name +'/:id', function (req, res, next) {
			collection.find(req.params.id).then(
				function (record) {
					res.header('last-Modified', new Date(record.updatedAt));
					res.send(record);
					next();
				},
				function () {
					next(new restify.ResourceNotFoundError(req.path()));
				}
			);
		});

		// Adds one item to the collection.
		server.post('/'+ name, function (req, res, next) {
			collection.insert(req.body);
			res.send(req.body.id);
			next();

			collection.flush();
		});

		// Replaces (updates) one item in the collection.
		server.put('/'+ name +'/:id', function (req, res, next) {
			req.body.id = req.params.id;
			collection.update(req.body);
			res.send(true);
			next();

			collection.flush();
		});

		// Deletes one item from the collection.
		server.del('/'+ name +'/:id', function (req, res, next) {
			collection.remove({id: req.params.id});
			res.send(true);
			next();

			collection.flush();
		});
	});

	// Serves static files (i.e. the client).
	server.get(/.*/, ecstatic({
		root: __dirname +'/../client/dist/',
	}));

  return eventToPromise(server, 'close');
});

//====================================================================

if (!module.parent) {
  require('exec-promise')(exports);
}
