'use strict';

/* Controllers */

var gaControllers = angular.module('gaControllers', []);

// list of countries
gaControllers.controller('CountryListController', ['$scope', 'CountryRestClient',
    function ($scope, CountryRestClient) {
        $scope.countries = CountryRestClient.query();
}]);

// main page
gaControllers.controller('CountryDetailController', ['$scope', '$routeParams', 'CountryRestClient',
    function ($scope, $routeParams, CountryRestClient) {
        $scope.errors = [];

        CountryRestClient.get({key: 'current'}, function (country) {
            $scope.country = country;
        }, function (response) {
            $scope.errors = response.data.errors;
        });
}]);
