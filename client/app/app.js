/* globals angular:false */

'use strict';

//====================================================================

require('angular');

require('angular-resource');
require('angular-ui-bootstrap-bower');
require('angular-ui-router');
require('angular-ui-utils/ui-utils');

//====================================================================

angular.module('ezac', [
	'ngResource',

	'ui.bootstrap',
	'ui.utils',
	'ui.router',
])
	.config([
		'$locationProvider',
		'$stateProvider',
		'$urlRouterProvider',
		function (
			$locationProvider,
			$stateProvider,
			$urlRouterProvider
		) {
			// Uses real URLs.
			// $locationProvider.html5Mode(true);

			// Redirects unmatched URLs to `/events`.
			$urlRouterProvider.otherwise('/events');

			// Sets up the different states for our module.
			$stateProvider
				.state('list-events', {
					url: '/events',
					controller: 'list-events',
					template: require('./list-events.jade'),
				})
				.state('about', {
					url: '/about',
					template: require('./about.jade'),
				})
				.state('new-event', {
					url: '/events/new',
					controller: 'new-event',
					template: require('./new-event.jade'),
				})
			;
		}
	])
	.service('events', ['$resource', function ($resource) {
		return $resource('events/:id', {}, {
			all: {
				method: 'GET',
				isArray: true,
			},
			create: { method: 'POST' },
			delete: { method: 'DELETE' },
			get: { method: 'GET' },
			save: { method: 'PUT' },
		});
	}])
	.controller('list-events', require('./list-events'))
	.controller('new-event', require('./new-event'))
;
