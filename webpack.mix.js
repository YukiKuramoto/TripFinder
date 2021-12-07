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
    // .js('resources/js/main.js', 'public/js')
    .js('resources/js/store.js', 'public/js')
    .js('resources/js/post.js', 'public/js')
    .js('resources/js/mypage.js', 'public/js')
    .js('resources/js/profile.js', 'public/js')
    .js('resources/js/searchpage.js', 'public/js')
    .js('resources/js/modal.js', 'public/js')
    .js('resources/js/jquery.tagsinput.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/jquery.tagsinput.scss', 'public/css')
    .sass('resources/sass/post.scss', 'public/css')
    .sass('resources/sass/planpage.scss', 'public/css')
    .sass('resources/sass/home.scss', 'public/css')
    .sass('resources/sass/mypage.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/formpage.scss', 'public/css')
    .sass('resources/sass/comment.scss', 'public/css')
    .sass('resources/sass/profile.scss', 'public/css')
    .sass('resources/sass/usersview.scss', 'public/css')
    .sass('resources/sass/searchpage.scss', 'public/css')
    .sass('resources/sass/spotview.scss', 'public/css');
