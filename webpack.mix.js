const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.browserSync({
    proxy: 'vinyl_shop.test',
    port: 3000
});

mix.version();

mix.disableNotifications();
