'use strict';

//====================================================================

var marked = require('marked');

//====================================================================

module.exports = ['$scope', '$stateParams', 'events',
	function ($scope, $stateParams, events) {
		var id = $stateParams.id;

		events.get({id: id}, function (event) {
			$scope.active = event.active;
			$scope.description = event.description && marked(event.description);
			$scope.name = event.name;
		});
	}
];
