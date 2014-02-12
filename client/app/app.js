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
					controller: require('./list-events'),
					template: require('./list-events.jade'),
				})
				.state('about', {
					url: '/about',
					template: require('./about.jade'),
				})
				.state('new-event', {
					url: '/events/new',
					controller: require('./new-event'),
					template: require('./new-event.jade'),
				})
				.state('event', {
					url: '/events/:id',
					controller: require('./show-event'),
					template: require('./show-event.jade'),
				})
			;
		}
	])
	.directive('ezacBindHtml', function () {
		return {
			restrict: 'A',
			scope: {
				ezacBindHtml: '@',
			},
			link: function ($scope, $el, $attrs) {
				$attrs.$observe('ezacBindHtml', function (value) {
					$el.html(value);
				});
			},
		};
	})
	.service('events', ['$resource', function ($resource) {
		var resource = $resource('events/:id', {}, {
			all: {
				method: 'GET',
				isArray: true,
			},
			create: { method: 'POST' },
			delete: { method: 'DELETE' },
			get: { method: 'GET' },
			save: { method: 'PUT' },
		});

		var events = [];
		var all = resource.all;
		resource.all = function () {
			all({}, function (entries) {
				events.length = 0;
				events.push.apply(events, entries);
			});
			return events;
		};

		return resource;
	}])
	.service('debounce', ['$timeout', function ($timeout) {
		return function (fn, delay) {
			var promise;
			fn = (function (fn) {
				return function () {
					promise = null;
					fn();
				};
			})(fn);

			return function () {
				if (promise)
				{
					$timeout.cancel(promise);
				}
				promise = $timeout(fn, delay);
			};
		};
	}])
;
