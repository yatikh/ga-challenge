'use strict';

/* Services */

var gaServices = angular.module('gaServices', ['ngResource']);

// rest manager for countries route set
gaServices.factory('CountryRestClient', ['$resource',
    function ($resource) {
        return $resource('api/countries/:key', {key: ''});
}]);
