'use strict';

//====================================================================

var marked = require('marked');

//====================================================================

module.exports = ['$scope', '$stateParams', 'events',
	function ($scope, $stateParams, events) {
		var id = $stateParams.id;

		var event = events.get(id);
		$scope.$watch(function () {
			return event;
		}, function () {
			$scope.active = event.active;
			$scope.description = event.description && marked(event.description);
			$scope.name = event.name;
		}, true);
	}
];
