const { series, task, src, dest, parallel, watch } = require('gulp');

const clean = require('gulp-clean');
const plumber = require('gulp-plumber');

const rename = require('gulp-rename');
const terser = require('gulp-terser');
const concat = require('gulp-concat');
const slash = require('gulp-slash');
const debug = require('gulp-debug');
const filter = require('gulp-filter');

const npm_main_node_files = require('gulp-main-node-files');
const clean_css = require('gulp-clean-css');
const sass = require('gulp-sass');
const bulk_sass = require('gulp-sass-bulk-import');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');

const imagemin = require('gulp-imagemin');
const pngquant = require('imagemin-pngquant');

const browser_sync = require('browser-sync');

// Development Assets
const css_dev = 'assets_dev/css/**/*.css';
const js_dev = 'assets_dev/js/**/*.js';
const plugins_dev = 'assets_dev/plugins/**/*';
const img_dev = 'assets_dev/img/**/*';
const sounds_dev = 'assets_dev/sounds/**/*';
const fonts_dev = 'assets_dev/fonts/**/*';
const sass_dev = 'assets_dev/scss/**/*.scss';
const sass_main_file = 'assets_dev/scss/style.scss';

// Production Assets
const css_dist = 'assets/css/';
const js_dist = 'assets/js/';
const plugins_dist = 'assets/plugins/';
const img_dist = 'assets/img/';
const sounds_dist = 'assets/sounds/';
const fonts_dist = 'assets/fonts/';
const sass_dist = 'assets/scss/';

// Modules Assets
const css_modules = 'application/modules/**/assets/css/*.css';
const sass_modules = 'application/modules/**/assets/scss/*.scss';
const js_modules = 'application/modules/**/assets/js/*.js';
const img_modules = 'application/modules/**/assets/img/*';

const css_dist_modules = 'assets/css/modules/';
const js_dist_modules = 'assets/js/modules/';
const img_dist_modules = 'assets/img/modules/';

// Npm main files overrides
const npm_main_files_overrides = {
    "jquery": [
        "./dist/jquery.min.js"
    ],
    "bootstrap-sass": [
        "./assets/stylesheets/_bootstrap.scss",
        "./assets/fonts/bootstrap/*.*"
    ],
    "admin-lte-sass": [
        "./build/js/app.js",
        "./build/bootstrap/js/bootstrap.js",
        "./build/scss/AdminLTE.scss",
    ],
    "cropper": [
        "./dist/cropper.min.css",
        "./dist/cropper.min.js"
    ],
    "font-awesome": [
        "./css/font-awesome.min.css",
        "./fonts/*.*"
    ],
    "@fancyapps/fancybox": [
        "./dist/jquery.fancybox.css",
        "./dist/jquery.fancybox.js"
    ],
    "noty": [
        "./js/noty/packaged/jquery.noty.packaged.min.js"
    ],
    "animate.css": [
        "./animate.css"
    ],
    "jquery-mask-plugin": [
        "./dist/jquery.mask.min.js"
    ],
    "eonasdan-bootstrap-datetimepicker-npm": [
        "./build/css/bootstrap-datetimepicker.css",
        "./build/js/bootstrap-datetimepicker.min.js"
    ],
    "moment": [
        "./min/moment-with-locales.min.js"
    ],
    "bootstrap-toggle": [
        "./js/bootstrap-toggle.min.js",
        "./css/bootstrap-toggle.min.css"
    ],
    "jquery-easy-loading": [
        "./dist/jquery.loading.min.css",
        "./dist/jquery.loading.min.js"
    ],
    "chosen": [
        "./public/chosen.jquery.js",
        "./public/chosen.css"
    ]
};

// Override Gulp Src with plumbered version
let srcPlumbed = function (...args) {
    return src(...args).pipe(plumber());
};

/**
 * --------- CLEAN
 */

// Clean Scripts Dist Folder
task('clean_scripts', function () {
    return srcPlumbed(js_dist, { read: false, allowEmpty: true })
        .pipe(clean());
});

// Clean Styles Dist Folder
task('clean_styles', function () {
    return srcPlumbed([css_dist, sass_dist], { read: false, allowEmpty: true })
        .pipe(clean());
});

// Clean Imagem Dist Folder
task('clean_images', function () {
    return srcPlumbed(img_dist, { read: false, allowEmpty: true })
        .pipe(clean());
});

// Clean Imagem Dist Folder
task('clean_fonts', function () {
    return srcPlumbed(fonts_dist, { read: false, allowEmpty: true })
        .pipe(clean());
});

// Clean Plugins Dist Folder
task('clean_plugins', function () {
    return srcPlumbed(plugins_dist, { read: false, allowEmpty: true })
        .pipe(clean());
});

// Clean Sounds Dist Folder
task('clean_sounds', function () {
    return srcPlumbed(sounds_dist, { read: false, allowEmpty: true })
        .pipe(clean());
});

/**
 * --------- NPM
 */

// Build a single CSS file with all main npm files
task('npm_css', function () {
    let css_filter = filter(['**/*.css']);

    return srcPlumbed(
        npm_main_node_files({
            overrides: npm_main_files_overrides,
            order: {}
        })
    )
        .pipe(css_filter)
        .pipe(debug({ 'title': 'npm_css css_filter' }))
        .pipe(concat('vendor.min.css'))
        .pipe(dest(sass_dist));
});

task('npm_css:dist', function () {
    let css_filter = filter(['**/*.css']);

    return srcPlumbed(
        npm_main_node_files({
            overrides: npm_main_files_overrides,
            order: {}
        })
    )
        .pipe(css_filter)
        .pipe(concat('vendor.min.css'))
        .pipe(clean_css())
        .pipe(dest(sass_dist));
});


// Build a single SCSS file with all main npm files
task('npm_sass', function () {
    return srcPlumbed('node_modules/admin-lte-sass/build/scss/AdminLTE.scss')
        .pipe(debug({ 'title': 'npm_sass' }))
        .pipe(concat('vendor.scss.css'))
        .pipe(sass(
            {
                outputStyle: 'nested',
                precison: 10,
                errLogToConsole: true,
                includePaths: ['.']
            }
        ))
        .pipe(autoprefixer('last 2 versions'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(sass_dist));
});

task('npm_sass:dist', function () {
    return srcPlumbed('node_modules/admin-lte-sass/build/scss/AdminLTE.scss')
        .pipe(concat('vendor.scss.css'))
        .pipe(sass(
            {
                outputStyle: 'nested',
                precison: 3,
                errLogToConsole: true,
                includePaths: ['.']
            }
        ))
        .pipe(clean_css())
        .pipe(autoprefixer('last 2 versions'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(sass_dist));
});


// Concatenate css and scss files from main npm files into a single file
task('npm_styles', series('npm_css', 'npm_sass', function () {
    return srcPlumbed(sass_dist + '*.*')
        .pipe(concat('vendor.min.css'))
        .pipe(dest(css_dist));
}));

task('npm_styles:dist', series('npm_css:dist', 'npm_sass:dist', function () {
    return srcPlumbed(sass_dist + '*.*')
        .pipe(concat('vendor.min.css'))
        .pipe(clean_css())
        .pipe(dest(css_dist));
}));

// Call npm_styles and then clean individual files from that task
task('npm_styles_and_clean', series('npm_styles', function () {
    let sass_dist_dir = sass_dist.slice(0, -1);
    return srcPlumbed(sass_dist_dir, { read: false })
        .pipe(clean());
}));

task('npm_styles_and_clean:dist', series('npm_styles:dist', function () {
    let sass_dist_dir = sass_dist.slice(0, -1);
    return srcPlumbed(sass_dist_dir, { read: false })
        .pipe(clean());
}));

// Build a single JS file with all main npm files
task('npm_scripts', function () {
    let js_filter = filter(['**/*.js']);

    return srcPlumbed(npm_main_node_files({
        overrides: npm_main_files_overrides,
        order: {
            'jquery': 1,
            'moment': 2
        }
    }))
        .pipe(js_filter)
        .pipe(debug({ 'title': 'npm_scripts js_filter' }))
        .pipe(concat('vendor.min.js'))
        .pipe(dest(js_dist));
});

task('npm_scripts:dist', function () {
    let js_filter = filter(['**/*.js']);

    return srcPlumbed(npm_main_node_files({
        overrides: npm_main_files_overrides,
        order: {
            'jquery': 1,
            'moment': 2
        }
    }))
        .pipe(js_filter)
        .pipe(concat('vendor.min.js'))
        .pipe(terser({ compress: { drop_console: true } }))
        .pipe(dest(js_dist));
});

// Copy all npm main font files to font dist folder
task('npm_fonts', function () {
    let fonts_filter = filter(['**/fonts/**/*.*']);

    return srcPlumbed(npm_main_node_files({
        overrides: npm_main_files_overrides,
        order: {}
    }))
        .pipe(fonts_filter)
        .pipe(debug({ 'title': 'npm_fonts fonts_filter' }))
        .pipe(dest(fonts_dist));
});

task('npm_fonts:dist', function () {
    let fonts_filter = filter(['**/fonts/**/*.*']);

    return srcPlumbed(npm_main_node_files({
        overrides: npm_main_files_overrides,
        order: {}
    }))
        .pipe(fonts_filter)
        .pipe(dest(fonts_dist));
});

/**
 * --------- SCRIPTS
 */

// Minify Scripts
task('scripts', function () {
    return srcPlumbed(js_dev)
        .pipe(concat('main.js'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(js_dist));
});

task('scripts:dist', function () {
    return srcPlumbed(js_dev)
        .pipe(concat('main.js'))
        .pipe(terser({ compress: { drop_console: true } }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(js_dist));
});

// Minify Modules Scripts
task('module_scripts', function () {
    return srcPlumbed(js_modules)
        .pipe(rename(function (path) {
            let module_path;

            //Se for widget precisa adicionar dentro da pasta widget/modulo/modulo.min.js
            if (path.dirname.indexOf('widget') !== -1) {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('widget/') + 7)) + path.basename + '/';
            } else {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('/') + 1));
            }

            path.dirname = js_dist_modules + module_path;
            path.basename += ".min";
        }))
        .pipe(dest('.'));
});

task('module_scripts:dist', function () {
    return srcPlumbed(js_modules)
        .pipe(terser({ compress: { drop_console: true } }))
        .pipe(rename(function (path) {
            let module_path;

            //Se for widget precisa adicionar dentro da pasta widget/modulo/modulo.min.js
            if (path.dirname.indexOf('widget') !== -1) {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('widget/') + 7)) + path.basename + '/';
            } else {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('/') + 1));
            }

            path.dirname = js_dist_modules + module_path;
            path.basename += ".min";
        }))
        .pipe(dest('.'));
});

/**
 * --------- STYLES
 */

// Minify Styles
task('styles', function () {
    return srcPlumbed(css_dev)
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(css_dist));
});

task('styles:dist', function () {
    return srcPlumbed(css_dev)
        .pipe(clean_css())
        .pipe(autoprefixer('last 2 versions'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(css_dist));
});

// Minify Modules Styles
task('module_styles', function () {
    return srcPlumbed(css_modules)
        .pipe(rename(function (path) {
            let module_path = path.dirname.slice(0, (path.dirname.indexOf('\\') + 1));
            path.dirname = css_dist_modules + module_path;
            path.basename += ".min";
        }))
        .pipe(dest('.'));
});

task('module_styles:dist', function () {
    return srcPlumbed(css_modules)
        .pipe(clean_css())
        .pipe(autoprefixer('last 2 versions'))
        .pipe(rename(function (path) {
            let module_path = path.dirname.slice(0, (path.dirname.indexOf('\\') + 1));
            path.dirname = css_dist_modules + module_path;
            path.basename += ".min";
        }))
        .pipe(dest('.'));
});

/**
 * --------- SASS
 */

// Compile sass main file with source map (with bulked sass module files) from dev folder to dist folder
task('sass_styles', function () {
    return srcPlumbed(sass_main_file)
        .pipe(bulk_sass())
        .pipe(rename({ suffix: '.min' }))
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: 'compressed' }))
        .pipe(sourcemaps.write())
        .pipe(dest(css_dist));
});

task('sass_styles:dist', function () {
    return srcPlumbed(sass_main_file)
        .pipe(bulk_sass())
        .pipe(rename({ suffix: '.min' }))
        .pipe(sass({ outputStyle: 'compressed' }))
        .pipe(dest(css_dist));
});

/**
 * --------- PLUGINS
 */

//Copy resources from plugins-dist that didn't make it in the bower-files
task('plugins', function () {
    return srcPlumbed(plugins_dev)
        .pipe(dest(plugins_dist));
});

task('plugins:dist', function () {
    return srcPlumbed(plugins_dev)
        .pipe(dest(plugins_dist));
});

/**
 * --------- IMAGES
 */

task('images', function () {
    return srcPlumbed(img_dev)
        .pipe(dest(img_dist));
});

task('images:dist', function () {
    return srcPlumbed(img_dev)
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{ removeViewBox: false }],
            use: [pngquant()]
        }))
        .pipe(dest(img_dist));
});


// Compress all images from modlues and send to dist folder
task('module_images', function () {
    return srcPlumbed(img_modules)
        .pipe(rename(function (path) {
            let module_path;

            //Se for widget precisa adicionar dentro da pasta widget/modulo/modulo.min.js
            if (path.dirname.indexOf('widget') !== -1) {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('widget/') + 7)) + path.basename + '/';
            } else {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('/') + 1));
            }

            path.dirname = img_dist_modules + module_path;
        }))
        .pipe(dest('.'));
});

task('module_images:dist', function () {
    return srcPlumbed(img_modules)
        .pipe(rename(function (path) {
            let module_path;

            //Se for widget precisa adicionar dentro da pasta widget/modulo/modulo.min.js
            if (path.dirname.indexOf('widget') !== -1) {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('widget/') + 7)) + path.basename + '/';
            } else {
                module_path = path.dirname.slice(0, (slash(path.dirname).indexOf('/') + 1));
            }

            path.dirname = img_dist_modules + module_path;
        }))
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{ removeViewBox: false }],
            use: [pngquant()]
        }))
        .pipe(dest('.'));
});


/**
 * --------- SOUNDS
 */

task('sounds', function () {
    return srcPlumbed(sounds_dev)
        .pipe(dest(sounds_dist));
});

task('sounds:dist', function () {
    return srcPlumbed(sounds_dev)
        .pipe(dest(sounds_dist));
});

/**
 * --------- FONTS
 */

task('fonts', function () {
    return srcPlumbed(fonts_dev)
        .pipe(dest(fonts_dist));
});

task('fonts:dist', function () {
    return srcPlumbed(fonts_dev)
        .pipe(dest(fonts_dist));
});

/**
 * --------- BROWSER SYNC
 */

task('browser_reload', function () {
    browser_sync.reload();
});

/* Prepare Browser-sync for localhost */
task('browser_sync', function () {
    browser_sync.init([css_dev, js_dev], {
        proxy: 'http://nano.incub72'
    });

    watch(['./*.php', './**/*.php', '!./**/logs/*.php'], { cwd: './' }, series('browser_reload'));
});

/**
 * --------- WATCH
 */
task('watch', function () {
    watch(js_dev, { cwd: './' }, parallel('scripts'));
    watch(css_dev, { cwd: './' }, parallel('styles'));
    watch(img_dev, { cwd: './' }, parallel('images'));
    watch(sass_main_file, { cwd: './' }, parallel('sass_styles'));
    watch(sass_dev, { cwd: './' }, parallel('sass_styles'));
    watch(sass_modules, { cwd: './' }, parallel('sass_styles'));
    watch(css_modules, { cwd: './' }, parallel('module_styles'));
    watch(js_modules, { cwd: './' }, parallel('module_scripts'));
    watch(img_modules, { cwd: './' }, parallel('module_images'));
    watch(plugins_dev, { cwd: './' }, parallel('plugins'));
    watch(sounds_dev, { cwd: './' }, parallel('sounds'));
    watch(fonts_dev, { cwd: './' }, parallel('fonts'));
});

/**
 * --------- COMPLEX TASKS
 */
task('clean', series('clean_scripts', 'clean_styles', 'clean_images', 'clean_fonts', 'clean_plugins', 'clean_sounds'));

task('npm', series('npm_scripts', 'npm_styles_and_clean', 'npm_fonts'));
task('develop', series('npm', 'scripts', 'module_scripts', 'styles', 'module_styles', 'sass_styles', 'plugins', 'images', 'module_images', 'sounds', 'fonts'));
task('develop_and_watch', series('develop', 'watch'));

task('npm:dist', series('npm_scripts:dist', 'npm_styles_and_clean:dist', 'npm_fonts:dist'));
task('dist', series('npm:dist', 'scripts:dist', 'module_scripts:dist', 'styles:dist', 'module_styles:dist', 'sass_styles:dist', 'plugins:dist', 'images:dist', 'module_images:dist', 'sounds:dist', 'fonts:dist'));

task('build', series('dist'));
task('default', series('develop_and_watch'));
