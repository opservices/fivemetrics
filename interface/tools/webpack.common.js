module.exports = {
  module: {
    loaders: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules|bower_components/,
        loader: 'babel'
      },
      {
        test: /\.json$/, loader: 'json'
      },
      {
        test: /\.(ico|gif|png|jpg|jpeg|svg|webp)$/,
        loaders: ["file?context=public&name=/[path][name].[ext]"],
        exclude: /node_modules/
      },
      {
        test: /\.woff(\?v=\d+\.\d+\.\d+)?$/,
        loader: "file?mimetype=application/font-woff&name=./public/fonts/[hash].[ext]"
      },
      {
        test: /\.woff2(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=application/font-woff&name=./public/fonts/[hash].[ext]"
      },
      {
        test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=application/octet-stream&name=./public/fonts/[hash].[ext]"
      },
      {
        test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
        loader: "file&name=./public/fonts/[hash].[ext]"
      },
      {
        test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
        loader: "url?limit=10000&mimetype=image/svg+xml&name=./public/fonts/[hash].[ext]"
      },
    ]
  },
  progress: true,
  resolve: {
    alias: {
	'jquery-ui': 'jquery-ui/ui'
    },
    modulesDirectories: [
      "src",
      "sass",
      "public",
      "node_modules"
    ],
    extensions: ["", ".json", ".js"]
  }
};
