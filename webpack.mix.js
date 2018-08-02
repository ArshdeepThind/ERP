const { mix } = require('laravel-mix');


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


mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/admin/app.scss','public/css/admin-app.css')
   .sass('resources/assets/employee/app.scss','public/css/employee-app.css')
   .options({
    processCssUrls: false
   })
   .js('resources/assets/admin/js/main-buid.js','public/js/admin-main.js')
   .scripts([
        	'public/plugins/slimScroll/jquery.slimscroll.min.js',
        	'public/plugins/fastclick/fastclick.js',
        	'public/plugins/iCheck/icheck.min.js',
        	'resources/assets/admin/js/app.min.js'
        ],'public/js/admin-app.js');


mix.copy('node_modules/intl-tel-input', 'public/plugins/intl-tel-input');

