/* globals window:false */

'use strict';

//====================================================================

require('angular');
var angular = window.angular;

require('angular-ui-bootstrap-bower');
require('angular-ui-router');
require('angular-ui-utils/ui-utils');

//====================================================================

angular.module('ezac', [
	'ui.bootstrap',
	'ui.utils',
	'ui.router',
])
	.config(function (
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
		;
	})
;
