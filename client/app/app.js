/* globals window:false */

'use strict';

//==============================================================================

require('angular');
var angular = window.angular;

require('angular-ui-bootstrap-bower');
require('angular-ui-router');

//==============================================================================

angular.module('ezac', [
	'ui.bootstrap',
	'ui.router',
]);
