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

'use strict';

/* Services */

var gaServices = angular.module('gaServices', ['ngResource']);

// rest manager for countries route set
gaServices.factory('CountryRestClient', ['$resource',
    function ($resource) {
        return $resource('api/countries/:key', {key: ''});
}]);

//# sourceMappingURL=app.js.map
