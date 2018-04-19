let mix = require('laravel-mix');

mix.sass('resources/assets/sass/app.scss', 'public/css')
    .copy('resources/assets/vendor/bootstrap/fonts', 'public/fonts')
    .copy('resources/assets/vendor/font-awesome/fonts', 'public/fonts')
    .styles(
        [
            'resources/assets/vendor/bootstrap/css/bootstrap.css',
            'resources/assets/js/plugins/jquery-ui/jquery-ui.min.css',
            'resources/assets/vendor/animate/animate.css',
            'resources/assets/vendor/font-awesome/css/font-awesome.css',
            'resources/assets/sass/plugins/steps/jquery.steps.css',
            'resources/assets/sass/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
            'resources/assets/sass/plugins/blueimp/css/blueimp-gallery.min.css',
            'resources/assets/sass/plugins/sweetalert/sweetalert.css',
            'resources/assets/sass/plugins/footable/footable.core.css',
            'resources/assets/sass/plugins/iCheck/custom.css',
            'resources/assets/sass/plugins/toastr/toastr.min.css',
            'resources/assets/sass/plugins/bootstrap-markdown/bootstrap-markdown.min.css',
            'resources/assets/sass/plugins/datapicker/datepicker3.css',
            'resources/assets/sass/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
            'resources/assets/sass/plugins/dropzone/dropzone.css'
        ],
        'public/css/vendor.css')
    .js(
        [
            'resources/assets/js/bootstrap.js',
            'resources/assets/js/plugins/jquery-ui/jquery-ui.min.js',
            'resources/assets/vendor/metisMenu/jquery.metisMenu.js',
            'resources/assets/vendor/slimscroll/jquery.slimscroll.min.js',
            'resources/assets/vendor/pace/pace.min.js',
            'resources/assets/js/plugins/jasny/jasny-bootstrap.min.js',
            'resources/assets/js/plugins/steps/jquery.steps.min.js',
            'resources/assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js',
            'resources/assets/js/plugins/footable/footable.all.min.js',
            'resources/assets/js/plugins/typehead/bootstrap3-typeahead.min.js',
            'resources/assets/js/plugins/iCheck/icheck.min.js',
            'resources/assets/js/plugins/bootstrap-markdown/bootstrap-markdown.js',
            'resources/assets/js/plugins/bootstrap-markdown/markdown.js',
            'resources/assets/js/plugins/datapicker/bootstrap-datepicker.js'
        ],
        'public/js/lib.js')
    .sourceMaps().version()
    .scripts(
        [
            'resources/assets/js/inspinia.js',
            'resources/assets/js/app.js'
        ],
        'public/js/app.js') .version();
