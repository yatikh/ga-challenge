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
