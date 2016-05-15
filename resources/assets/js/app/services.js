'use strict';

/* Services */

var gaServices = angular.module('gaServices', ['ngResource']);

// rest manager for countries route set
gaServices.factory('CountryRestClient', ['$resource',
    function ($resource) {
        return $resource('api/countries/:key', {key: ''});
}]);

// rest manager for phonenumbers route set
gaServices.factory('PhonenumberRestClient', ['$resource',
    function ($resource) {
        return $resource(
            'api/phonenumbers/:country/:current',
            {
                current: ''
            }
        );
}]);

gaServices.factory('HttpRequestInterceptor', function () {
    return {
        request: function (config) {
            // set csrf token
            config.headers['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            return config;
        }
    };
});
