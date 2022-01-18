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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/profile.js', 'public/js')
    .js('resources/js/functions/tags.js', 'public/js')
    .js('resources/js/functions/tabs.js', 'public/js')
    .js('resources/js/functions/accordion.js', 'public/js')
    .js('resources/js/functions/modal.js', 'public/js')
    .js('resources/js/functions/jquery.tagsinput.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/home.scss', 'public/css')
    .sass('resources/sass/mypage.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/formpage.scss', 'public/css')
    .sass('resources/sass/comment.scss', 'public/css')
    .sass('resources/sass/profile.scss', 'public/css')
    .sass('resources/sass/usersview.scss', 'public/css')
    .sass('resources/sass/searchpage.scss', 'public/css')
    .sass('resources/sass/spotview.scss', 'public/css')
    .sass('resources/sass/functions/jquery.tagsinput.scss', 'public/css')
    .sass('resources/sass/functions/accordion.scss', 'public/css')
    .sass('resources/sass/functions/tabs.scss', 'public/css')
    .sass('resources/sass/mobile/app_mobile.scss', 'public/css');
