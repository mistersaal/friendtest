const mix = require('laravel-mix');

mix.setPublicPath('public');
mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[name].js',
    },
});
mix.disableNotifications();

mix.js('resources/js/main.js', 'public/js')
    .version();
