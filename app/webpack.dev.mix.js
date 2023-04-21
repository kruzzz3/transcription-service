const mix = require('laravel-mix');

mix
    .sourceMaps(false)
    .version()
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
;
