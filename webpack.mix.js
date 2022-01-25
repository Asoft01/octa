const mix = require('laravel-mix');

// hack to version image
// https://github.com/JeffreyWay/laravel-mix/issues/1193
mix.copyOutsideMixWorkflow = function (from, to) {
    new File(from).copyTo(new File(to).path());

    return this;
}.bind(mix);

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

mix.setPublicPath('public')
    .setResourceRoot('../') // Turns assets paths in css relative to css file
    // .options({
    //     processCssUrls: false,
    // })
    .styles([
        'resources/assets/css/AC/style.css',
        'resources/assets/css/AC/dashicons-min.css',
        'resources/assets/css/AC/tooltipster.bundle.min.css'
    ], 'public/css/all.css')
    .styles('resources/assets/css/summernote-bs4.min.css', 'public/css/sm.min.css')
    .styles('resources/assets/css/dropzone.min.css', 'public/css/dz.min.css')
    .styles('resources/assets/css/summernote-lite-darkly.min.css', 'public/css/summernote-lite-darkly.min.css')
    .sass('resources/sass/frontend/app.scss', 'css/frontend.css')
    .sass('resources/sass/backend/app.scss', 'css/backend.css')
    .scripts([
        'resources/assets/js/AC/bideo.js',
        'resources/assets/js/AC/navigation.js',
        'resources/assets/js/AC/jquery.flexslider-min.js',
        'resources/assets/js/AC/jquery-asRange.min.js',
        'resources/assets/js/AC/owl.carousel.min.js',
        'resources/assets/js/AC/scripts.js',
        'resources/assets/js/AC/tooltipster.bundle.min.js',
        'resources/assets/js/AC/videojs.persistvolume.js',
        'resources/assets/js/AC/videojs.framebyframe.js'
    ], 'public/js/all.js')
    .scripts('resources/assets/js/dropzone.min.js', 'public/js/dz.min.js')
    .scripts('resources/assets/js/summernote-bs4.min.js', 'public/js/sm.min.js')
    .scripts('resources/assets/js/printThis.js', 'public/js/printThis.js')
    .js('resources/js/frontend/app.js', 'js/frontend.js')
    .js([
        'resources/js/backend/before.js',
        'resources/js/backend/app.js',
        'resources/js/backend/after.js'
    ], 'js/backend.js')
    .extract([
        // Extract packages from node_modules to vendor.js
        'jquery',
        'bootstrap',
        'popper.js',
        'axios',
        'sweetalert2',
        'lodash',
        'video.js'
    ])
    .copyOutsideMixWorkflow('resources/assets/', 'public')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version(["public/img"])
        .options({
            // Optimize JS minification process
            terser: {
                cache: true,
                parallel: true,
                sourceMap: true
            }
        });
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map'
    })
    .browserSync('wip.agora.community.loc');
}
