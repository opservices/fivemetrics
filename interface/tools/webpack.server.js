var fs = require('fs');
var path = require('path');
var webpack = require('webpack');
var deepmerge = require('deepmerge');
var webpackCommonConfig = require('./webpack.common');

var nodeModules = {};
fs.readdirSync('node_modules')
  .filter(function(x) {
    return ['.bin'].indexOf(x) === -1;
  })
  .forEach(function(mod) {
    nodeModules[mod] = 'commonjs ' + mod;
  });

var sourceMapSupportModule = "require('source-map-support').install({environment: 'node'});\n\n";

var output = { path: path.join(process.cwd(), 'tmp'), filename: 'bundle.js' };

if (process.env.NO_OUTPUT_PATH) {
  output = { filename: 'server.js' };
}

var loaders = webpackCommonConfig.module.loaders.concat();
loaders.push({ test: /\.scss$/, loader: 'null' });
//loaders.push({ test: /\.css$/, loader: 'null' });

delete webpackCommonConfig.module;

module.exports = deepmerge({
  devtool: 'source-map',
  entry: [
    './tools/server.babel.js'
  ],
  output: output,
  target: 'node',
  module: {
    loaders: loaders
  },
  plugins: [
    new webpack.BannerPlugin(sourceMapSupportModule, {
      raw: true,
      entryOnly: true
    }),
    new webpack.NoErrorsPlugin(),
    new webpack.DefinePlugin({__CLIENT__: false, __SERVER__: true, __PRODUCTION__: false, __DEV__: true, "process.env.NODE_ENV": '"'+process.env.NODE_ENV+'"'}),
    new webpack.IgnorePlugin(/vertx/),
    new webpack.ProvidePlugin({
        $: "jquery",
        jQuery: "jquery"
    })
  ],

  externals: nodeModules
}, webpackCommonConfig);
