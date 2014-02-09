'use strict';

//====================================================================

module.exports = ['$scope', 'events', function ($scope, events) {
	$scope.active = true;

	$scope.createEvent = function (name, description, active) {
		events.create({
			name: name,
			description: description,
			active: active,
		});
	};

	$scope.openDatePicker = function ($event) {
		$event.preventDefault();
		$event.stopPropagation();

		$scope.datePickerOpened = !$scope.datePickerOpened;
	};
}];
