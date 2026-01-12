const mix = require('laravel-mix');
const path = require('path');
const { exec } = require('child_process');

mix.alias({
    ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
});

mix.extend('ziggy', new class {
    register(config = {}) {
        this.watch = config.watch ?? ['routes/*.php'];
        this.path = config.path ?? '';
        this.enabled = true;//config.enabled ?? !Mix.inProduction();
    }

    boot() {
        if (!this.enabled) return;

        const command = () => exec(
            `php artisan ziggy:generate ${this.path}`,
            (error, stdout, stderr) => console.log(stdout)
        );

        command();

        if (Mix.isWatching() && this.watch) {
            ((require('chokidar')).watch(this.watch,{usePolling: true}))
                .on('all', (path) => {
                    console.log(`${path} changed...`);
                    command();
                });
        };
    }
}());
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/admin/app.js", "public/assets/js/admin")
    .js('resources/js/app.js', 'public/assets/js')
    .js('resources/js/jsvalidation.js', 'public/assets/js')
    .ziggy();
mix.copyDirectory("resources/images", "public/assets/images");
mix.copyDirectory("resources/fonts", "public/assets/fonts");
mix.copyDirectory("resources/css", "public/assets/css");

mix.sass(
        "resources/scss/admin/admin.scss",
        "public/assets/css/admin/admin.css"
    )
    .options({
        processCssUrls: false,
    });
mix.scripts(
    [
        'resources/js/admin/bundle/jquery.min.js',
        'resources/js/admin/bundle/bootstrap.bundle.min.js',
        'resources/js/admin/bundle/nioapp.min.js',
        'resources/js/admin/bundle/simplebar.min.js',
        'resources/js/admin/bundle/select2.full.min.js',
        'resources/js/admin/bundle/sweetalert2.min.js',
        'resources/js/admin/bundle/Chart.min.js',
        /** DataTable */
        'resources/js/admin/bundle/jquery.dataTables.min.js',
        'resources/js/admin/bundle/dataTables.responsive.min.js',
        'resources/js/admin/bundle/dataTables.bootstrap4.min.js',
        'resources/js/admin/bundle/responsive.bootstrap4.min.js',
        /** Date Picker */
        'resources/js/admin/bundle/bootstrap-datepicker.min.js',
        'resources/js/admin/bundle/jquery.timepicker.js',
        'resources/js/jsvalidation.js',
        'resources/js/admin/common.js',
        'resources/js/admin/default/scripts.js',
        'resources/js/admin/default/summernote.js',
        'resources/js/admin/default/editors.js',
        'resources/js/cropper.min.js',
        ],
        "public/assets/js/admin/admin-app.js"
    );
mix.js(
    ["resources/js/admin/auth/login.js"],
    "public/assets/js/admin/auth/login.js"
)
.js(
    ["resources/js/admin/auth/otp.js"],
    "public/assets/js/admin/auth/otp.js"
)
.js(
    ["resources/js/admin/auth/change-password.js"],
    "public/assets/js/admin/auth/change-password.js"
)
.js(
    ["resources/js/admin/auth/reset-password.js"],
    "public/assets/js/admin/auth/reset-password.js"
)
.js(
    ["resources/js/admin/cropper/image-cropper.js"],
    "public/assets/js/admin/cropper/image-cropper.js"
)
.js(
    ["resources/js/client/cropper/image-cropper.js"],
    "public/assets/js/client/cropper/image-cropper.js"
);
mix.js(
    ["resources/js/admin/dashboard-chart.js"],
    "public/assets/js/admin/dashboard-chart.js"
)
.js(
    ["resources/js/admin/faq/index.js"],
    "public/assets/js/admin/faq/index.js"
);
mix.js(
    ["resources/js/admin/user/index.js"],
    "public/assets/js/admin/user/index.js"
)
mix.js(
    ["resources/js/admin/data/index.js"],
    "public/assets/js/admin/data/index.js"
)
mix.js(
    ["resources/js/admin/user/user-details.js"],
    "public/assets/js/admin/user/user-details.js"
)
.js(
    ["resources/js/admin/cms/index.js"],
    "public/assets/js/admin/cms/index.js"
)
.js(
    ["resources/js/admin/profile/index.js"],
    "public/assets/js/admin/profile/index.js"
)
.js(
    ["resources/js/admin/setting/index.js"],
    "public/assets/js/admin/setting/index.js"
)
.js(
    ["resources/js/admin/role/index.js"],
    "public/assets/js/admin/role/index.js"
)
.js(
    ["resources/js/admin/staff/index.js"],
    "public/assets/js/admin/staff/index.js"
)
.js(
    ["resources/js/client/auth/login.js"],
    "public/assets/js/client/auth/login.js"
)
.js(
    ["resources/js/client/auth/register.js"],
    "public/assets/js/client/auth/register.js"
)
.js(
    ["resources/js/client/dashboard-chart.js"],
    "public/assets/js/client/dashboard-chart.js"
)
.js(
    ["resources/js/client/auth/change-password.js"],
    "public/assets/js/client/auth/change-password.js"
)
.js(
    ["resources/js/client/auth/reset-password.js"],
    "public/assets/js/client/auth/reset-password.js"
)
.js(
    ["resources/js/admin/client/index.js"],
    "public/assets/js/admin/client/index.js"
)
.js(
    ["resources/js/admin/client/client-details.js"],
    "public/assets/js/admin/client/client-details.js"
)
.js(
    ["resources/js/client/profile/index.js"],
    "public/assets/js/client/profile/index.js"
);
mix.js(
    ["resources/js/admin/product/index.js"],
    "public/assets/js/admin/product/index.js"
)
.js(
    ["resources/js/admin/product/add.js"],
    "public/assets/js/admin/product/add.js"
)
.js(
    ["resources/js/admin/product/edit.js"],
    "public/assets/js/admin/product/edit.js"
)
.js(
    ["resources/js/admin/product/raty.min.js"],
    "public/assets/js/admin/product/raty.min.js"
)
.js(
    ["resources/js/admin/product/product-details.js"],
    "public/assets/js/admin/product/product-details.js"
)
.js(
    ["resources/js/admin/fsn/index.js"],
    "public/assets/js/admin/fsn/index.js"
)
.js(
    ["resources/js/client/basicudi/index.js"],
    "public/assets/js/client/basicudi/index.js"
)
.js(
    ["resources/js/admin/basicudi/index.js"],
    "public/assets/js/admin/basicudi/index.js"
)
.js(
    ["resources/js/client/user/index.js"],
    "public/assets/js/client/user/index.js"
)
.js(
    ["resources/js/client/user/user-details.js"],
    "public/assets/js/client/user/user-details.js"
)
.js(
    ["resources/js/client/auth/otp.js"],
    "public/assets/js/client/auth/otp.js"
)
.js(
    ["resources/js/client/fsn/index.js"],
    "public/assets/js/client/fsn/index.js"
)
.js(
    ["resources/js/client/product/index.js"],
    "public/assets/js/client/product/index.js"
)
.js(
    ["resources/js/client/product/raty.min.js"],
    "public/assets/js/client/product/raty.min.js"
)
.js(
    ["resources/js/client/product/product-details.js"],
    "public/assets/js/client/product/product-details.js"
)
.js(
    ["resources/js/client/product/add.js"],
    "public/assets/js/client/product/add.js"
)
.js(
    ["resources/js/client/product/edit.js"],
    "public/assets/js/client/product/edit.js"
)
.js(
    ["resources/js/admin/notification/notification.js"],
    "public/assets/js/admin/notification/notification.js"
)
.js(
    ["resources/js/client/notification/notification.js"],
    "public/assets/js/client/notification/notification.js"
);
mix.copyDirectory('resources/images/flags', 'public/assets/images/flags');
mix.version();
