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
