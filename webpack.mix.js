// eslint-disable-next-line import/no-extraneous-dependencies
const mix = require('laravel-mix');
// const path = require('path');

mix.options({
  postCss: [
    // require('rtlcss'),
  ],
  runtimeChunkPath: 'assets',
  // processCssUrls: false,
  resourceRoot: '../../../',
});

mix.autoload({
  jquery: ['$', 'window.jQuery', 'jQuery'],
});

mix.extract('assets/vendor.js');

mix.js('admin/js/spotify-wordpress-elementor-admin.js', 'assets/admin/js');
mix.js('frontend/js/spotify-wordpress-elementor-public.js', 'assets/frontend/js');

mix.postCss('admin/css/spotify-wordpress-elementor-admin.css', 'assets/admin/css');
mix.postCss('frontend/css/spotify-wordpress-elementor-public.css', 'assets/frontend/css');
