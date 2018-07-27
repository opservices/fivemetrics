var path = require('path');
var rtlcss = require('rtlcss');
var webpack = require('webpack');
var deepmerge = require('deepmerge');
var autoprefixer = require('autoprefixer');
var blessPlugin = require('bless-webpack-plugin');
var webpackCommonConfig = require('./webpack.common');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

var isProduction = process.env.NODE_ENV === 'production';

var sourceMap = false;

if (process.env.SOURCEMAP === 'true') {
  sourceMap = true;
}

var wds = {
  hostname: process.env.WP_HOST || "localhost",
  port: process.env.WP_PORT || 8079
};

var wdsPath = "http://" + wds.hostname + ":" + wds.port;
var publicPath = wdsPath + "/assets/";

var devtool = '';
var entry = {
  'app': ['./src/main.js'],
  'main': ['./sass/main.scss'],
  //'main-rtl': ['./sass/main.rtl.scss'],
  'plugins': ['./src/plugins.js']
};

var plugins = [
  new webpack.DefinePlugin({__CLIENT__: true, __SERVER__: false, __PRODUCTION__: isProduction, __DEV__: !isProduction, "process.env.NODE_ENV": '"'+process.env.NODE_ENV+'"', __DEVTOOLS__: true})
];

if (process.env.EXTRACT_TEXT_PLUGIN === 'true') {
  plugins.unshift(new ExtractTextPlugin('css/[name].css'));
  plugins.unshift(blessPlugin({ imports: true, compress: true }));
}

if (isProduction) {
  plugins.unshift(new webpack.optimize.OccurrenceOrderPlugin());
  plugins.unshift(new webpack.optimize.DedupePlugin());
  plugins.unshift(new webpack.optimize.UglifyJsPlugin({
    mangle: false,
    compress: {
      unused: false,
      warnings: false
    },
    sourceMap: sourceMap
  }));
} else {
  plugins.unshift(new webpack.HotModuleReplacementPlugin());
}

function getStyleLoader(prefixer) {
  var s = '';

  if (sourceMap) s = 'sourceMap';

  if (process.env.EXTRACT_TEXT_PLUGIN === 'false') {
    return [
      'style',
      'css?-minimize&importLoaders=1&root=../public&' + s,
      'postcss-loader?pack='+prefixer+'!sass?' + s
    ];
  }

  return [
    ExtractTextPlugin.loader({
      extract: true,
      omit: 1
    }),
    'style',
    'css?-minimize&importLoaders=1&' + s,
    'postcss-loader?pack='+prefixer+'&' + s,
    'sass?' + s
  ];
}

devtool = sourceMap ? 'source-map' : '';

if (!isProduction) {
  for (var key in entry) {
    if (entry.hasOwnProperty(key)) {
      entry[key].push("webpack/hot/only-dev-server");
    }
  }

  entry.app.unshift("react-hot-loader/patch");

  entry.devServerClient = "webpack-dev-server/client?" + wdsPath;
}

var ltrloaders = getStyleLoader('normalprefixer');
var rtlloaders = getStyleLoader('rtlprefixer');

if (process.env.RTL !== 'true') {
  rtlloaders = ['null-loader'];
}

var loaders = webpackCommonConfig.module.loaders.concat();
// ltr/rtl loaders
loaders.push({ test: function(absPath) {
  if (absPath.search('rtl.scss') !== -1) {
    return true;
  }
  return false;
}, loaders: rtlloaders });
loaders.push({ test: function(absPath) {
  if (absPath.search('rtl.scss') === -1
   && absPath.search('.scss') !== -1) {
    return true;
  }
  return false;
}, loaders: ltrloaders });

// script loader for plugins.js
var pluginLoaders = ['script'];
if (isProduction) {
  pluginLoaders.push('uglify');
}
loaders.push({
  test: /(\/|\\)public(\/|\\)(.*?)\.js$/,
  loaders: pluginLoaders
});

delete webpackCommonConfig.module;

module.exports = deepmerge({
  cache: true,
  debug: true,
  devtool: devtool,
  entry: entry,
  module: {
    loaders: loaders
  },
  postcss: function() {
    return {
      normalprefixer: [ autoprefixer({ browsers: ['last 2 versions', '> 1%', 'ie 9'] }) ],
      rtlprefixer: [ autoprefixer({ browsers: ['last 2 versions', '> 1%', 'ie 9'] }), rtlcss ]
    };
  },
  devServer: {
    contentBase: wdsPath,
    publicPath: publicPath,
    hot:        true,
    inline:     false,
    lazy:       false,
    quiet:      false,
    noInfo:     true,
    headers:    { "Access-Control-Allow-Origin": "*" },
    stats:      { colors: true },
    host:       wds.hostname,
    port:       wds.port
  },
  output: {
    path: path.join(process.cwd(), 'public'),
    publicPath: isProduction ? '/' : publicPath,
    chunkFilename: 'js/[name].js',
    filename: 'js/[name].js',
  },
  plugins: plugins,
}, webpackCommonConfig);
