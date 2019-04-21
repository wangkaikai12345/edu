let mix = require('laravel-mix');
import multiplentry from './webpack_config/multiplentry';

const uglify = require('uglifyjs-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const env = process.env.NODE_ENV === 'production';

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

const globCssPath = 'resources/assets/sass/user/**/*.scss'
    , globJsPath = 'resources/assets/js/user/**/*.js'
    , cssMix = new multiplentry(globCssPath, 'css')
    , jsMix = new multiplentry(globJsPath, 'js');

const plugins = env ? [
    new uglify(),
    // new OptimizeCssAssetsPlugin(),
] : [];

cssMix.buildAssets();
jsMix.buildAssets();

mix.js('resources/assets/js/theme.js', 'public/js')
    .sass('resources/assets/sass/theme.scss', 'public/css')
    .browserSync({
        proxy: 'eduplayer.test'
    })
    .webpackConfig({
        plugins,
        resolve: {
            alias: {
                Dplayer: path.resolve(__dirname, 'public/tools/dplayer/dist/'),
            }
        }
    });