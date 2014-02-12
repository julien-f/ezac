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
	.service('ezacCollectionFactory', ['$resource', function ($resource) {
		return function (name) {
			var adapter = $resource(name +'/:id', {}, {
				all: {
					method: 'GET',
					isArray: true,
				},
				create: { method: 'POST' },
				delete: { method: 'DELETE' },
				get: { method: 'GET' },
				save: { method: 'PUT' },
			});

			var collection = [];

			return {
				all: function () {
					adapter.all(function (all) {
						collection.length = 0;
						collection.push.apply(collection, all);
					});
					return collection;
				},
				create: function (properties) {
					adapter.create(properties);
				},
				delete: function (id) {
					adapter.delete({id: id});
				},
				get: function (id) {
					return adapter.get({id: id});
				},
				save: function (properties) {
					adapter.save(properties);
				},
			};
		};
	}])
	.service('events', ['ezacCollectionFactory', function (factory) {
		return factory('events');
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
