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
            templateUrl: 'views/partials/main.html',
            controller: 'CountryDetailController'
        }).
        when('/countries', {
            templateUrl: 'views/partials/country-list.html',
            controller: 'CountryListController'
        }).
        otherwise({
            redirectTo: '/'
        });
}]);

gaChallengeApp.config(['$httpProvider',
    function ($httpProvider) {
        $httpProvider.interceptors.push('HttpRequestInterceptor');
}]);

'use strict';

/* Controllers */

var gaControllers = angular.module('gaControllers', []);

// main page
gaControllers.controller('CountryDetailController', ['$scope', '$location', 'CountryRestClient', 'PhonenumberRestClient',
    function ($scope, $location, CountryRestClient, PhonenumberRestClient) {
        CountryRestClient.get({key: 'current'}, function (country) {
            $scope.country = country;
            $scope.showPurchasingForm = false;
            $scope.currentNumber = null;

            PhonenumberRestClient.get(
                {country: country.iso, current: 'current'},
                function (phonenumber) {
                    $scope.currentNumber = phonenumber.number;
                    $scope.showPurchasingForm = false;
                },
                function (response) {
                    $scope.showPurchasingForm = true;

                    $scope.phonenumber = {
                        value: null,
                        options: null
                    };

                    // buy a number
                    $scope.submit = function() {
                        PhonenumberRestClient.save(
                            {country: '', phonenumber: $scope.phonenumber.value},
                            function (phonenumber) {
                                $scope.showPurchasingForm = false;
                                $scope.currentNumber = phonenumber.number;
                                $scope.errors = null;
                            }, function (response) {
                                $scope.showPurchasingForm = true;
                                $scope.errors = response.data.errors;
                        });
                    };
                });
        }, function (response) {
            $location.path('/countries');
        });
}]);


// list of countries
gaControllers.controller('CountryListController', ['$scope', 'CountryRestClient',
    function ($scope, CountryRestClient) {
        CountryRestClient.query(function (list) {
            $scope.list = list;
            $scope.errors = null;
        }, function (response) {
            $scope.errors = response.data.errors;
        });
}]);


// save country
gaControllers.controller('CountryKeepController', ['$scope', '$location', 'CountryRestClient',
    function ($scope, $location, CountryRestClient) {
        $scope.submit = function() {
            CountryRestClient.save($scope.$parent.country, function () {
                $scope.errors = null;
                $location.path('/');
            }, function (response) {
                $scope.errors = response.data.errors;
            });
        };
}]);

'use strict';

/* Controllers */

var gaDirectives = angular.module('gaDirectives', []);

gaDirectives.directive('gaPhonenumbersOptions', ['PhonenumberRestClient', function (PhonenumberRestClient) {
    return {
        restrict: 'EA',
        require: 'ngModel',
        scope: {
            options: '='
        },
        link: function (scope, element, $attrs, $ngModel) {
            // Ajax loading notification
            scope.options = ["Loading..."];

            // Control var to prevent infinite loop
            scope.loaded = false;

            element.bind('mousedown', function() {
                if (!scope.loaded) {
                    PhonenumberRestClient.query({
                        country: scope.$parent.country.iso
                    }, function (list) {
                        scope.options = list;
                        scope.loaded = true;
                    }, function (response) {
                        console.error(response.data.errors);
                    });
                }
            });
        }
    }
}]);

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

//# sourceMappingURL=app.js.map
