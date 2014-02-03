'use strict';

//====================================================================

module.exports = ['$scope', 'events', function ($scope, events) {
	$scope.events = events.all();

	$scope.createEvent = function (name, description, active) {
		console.log(name, description, active);
		events.create({
			name: name,
			description: description,
			active: active,
		})
	};
}];
