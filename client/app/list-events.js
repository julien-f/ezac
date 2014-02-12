'use strict';

//====================================================================

module.exports = ['$scope', 'events', function ($scope, events) {
	$scope.events = events.all();

	$scope.delete = function (id) {
		events.delete(id);
		$scope.events = events.all();
	};
}];
