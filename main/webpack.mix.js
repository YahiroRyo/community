const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig(***REMOVED***
  devServer: ***REMOVED***
    host: '0.0.0.0',
    proxy: ***REMOVED***
      '*': 'http://localhost:8000'
    ***REMOVED***
  ***REMOVED***
***REMOVED***);


mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');
