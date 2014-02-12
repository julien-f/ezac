'use strict';

//====================================================================

var marked = require('marked');

//====================================================================

module.exports = ['$scope', '$state', 'debounce', 'events',
	function ($scope, $state, debounce, events) {
		$scope.active = true;

		var updateDescriptionPreview = function () {
			var desc = $scope.description;
			$scope.descriptionPreview = desc ? marked(desc) : '';
		};
		$scope.$watch('description', debounce(updateDescriptionPreview, 250));

		$scope.createEvent = function (name, description, active) {
			events.create({
				name: name,
				description: description,
				active: active,
			}, function () {
				$state.go('list-events');
			});
		};

		$scope.openDatePicker = function ($event) {
			$event.preventDefault();
			$event.stopPropagation();

			$scope.datePickerOpened = !$scope.datePickerOpened;
		};
	}
];
