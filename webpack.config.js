const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');

Encore
// directory where all compiled assets will be stored
    .setOutputPath('public/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/build/')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // provide jquery
    .autoProvidejQuery()

    // enable sass loader
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
    })

    // New setting ...
    .enableSingleRuntimeChunk()

    // moment.js fix
    .addPlugin(new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/))

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // define entry point for backend
    .addEntry('application', [
        './assets/app.js',
    ])

    .addEntry('datatable', [
        './assets/datatable.js',
        './assets/datatable.scss'
    ])
;

// Use polling instead of inotify
const config = Encore.getWebpackConfig();

config.watchOptions = {
    poll: true,
    ignored: /node_modules/
};

// Export the final configuration
module.exports = config;