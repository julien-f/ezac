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
		//$locationProvider.html5Mode(true);

		// Redirects unmatched URLs to `/`.
		$urlRouterProvider.otherwise('/');

		// Sets up the different states for our module.
		$stateProvider
			.state('home', {
				url: '/',
				template: require('./home.jade'),
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
	}])
	.service('events', ['$resource', function ($resource) {
		return $resource('events/:id.json', {}, {
			all: {
				method: 'GET',
				isArray: true,
			},
			create: { method: 'PUT' },
			get: { method: 'GET' },
			save: { method: 'POST' },
		});
	}])
	.controller('new-event', require('./new-event'))
;
