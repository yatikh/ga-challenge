'use strict';

/* App Module */

var gaChallengeApp = angular.module('gaChallengeApp', [
    'ngRoute',
    'gaControllers',
    'gaServices'
]);


// routes
gaChallengeApp.config(['$routeProvider',
  function ($routeProvider) {
    $routeProvider.
    when('/', {
        templateUrl: 'views/partials/country-detail.html',
        controller: 'CountryDetailController'
    }).
    // when('/countries', {
    //     templateUrl: 'views/partials/country-list.html',
    //     controller: 'CountryListController'
    // }).
    otherwise({
        redirectTo: '/countries'
    });
}]);
