var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var paths = {
    flags: './vendor/bower_components/flag-icon-css/',
    bower: './vendor/bower_components/'
};

elixir(function(mix) {
    mix.sass('app.scss', 'public/css/')
        .copy(paths.flags + 'flags/**', 'public/flags')
        .styles(['./public/css/app.css', paths.flags + 'css/flag-icon.min.css'], 'public/css/app.css')
        .scripts(['app/*.js'], 'public/js/app.js')
        .scripts([
            'angular/angular.js',
            'angular-route/angular-route.js',
            'angular-resource/angular-resource.js'
        ], 'public/js/angular.js', paths.bower)
        .copy('./resources/assets/js/app/views', 'public/views')
    ;
});
