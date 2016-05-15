'use strict';

/* App Module */

var gaChallengeApp = angular.module('gaChallengeApp', [
    'ngRoute',
    'gaControllers',
    'gaServices',
    'gaDirectives'
]);

// routes
gaChallengeApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.
        when('/', {
            templateUrl: 'views/partials/country-detail.html',
            controller: 'CountryDetailController'
        }).
        when('/countries', {
            templateUrl: 'views/partials/country-list.html',
            controller: 'CountryListController'
        }).
        otherwise({
            redirectTo: '/countries'
        });
}]);

gaChallengeApp.config(['$httpProvider',
    function ($httpProvider) {
        $httpProvider.interceptors.push('HttpRequestInterceptor');
}]);
