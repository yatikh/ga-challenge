module.exports = function(config){
  config.set({

    basePath : '../../',

    files : [
      'vendor/bower_components/angular/angular.js',
      'vendor/bower_components/angular-route/angular-route.js',
      'vendor/bower_components/angular-resource/angular-resource.js',
      'vendor/bower_components/angular-animate/angular-animate.js',
      'vendor/bower_components/angular-mocks/angular-mocks.js',
      'vendor/js/**/*.js',
      'tests/client/unit/**/*.js'
    ],

    autoWatch : true,

    frameworks: ['jasmine'],

    browsers : ['Chrome', 'Firefox'],

    plugins : [
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-jasmine'
            ],

    junitReporter : {
      outputFile: 'test_out/unit.xml',
      suite: 'unit'
    }

  });
};