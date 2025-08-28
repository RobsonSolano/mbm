var path = require('path');
var webpack = require('webpack');
var BrowserSyncPlugin = require('browser-sync-webpack-plugin');

module.exports = {
  devtool: 'source-map',
  entry: [
  'webpack-dev-server/client?http://localhost:3000',
  'webpack/hot/dev-server',
  './build/js/app.js',
  './build/bootstrap/js/bootstrap.js',
  './build/scss/AdminLTE.scss'
  ],
  output: {
    path: path.join(__dirname,'dist'),
    filename: 'bundle.js',
    publicPath: '/dist'
  },
  plugins: [
  new BrowserSyncPlugin({
    files: [
   {
     match: ['./examples/**/*.html']
   }
   ],
   host: 'localhost',
   port: 5000,
   proxy: 'http://localhost:3000/'
 },{
   reload: false
 }),
  new webpack.HotModuleReplacementPlugin()
  ],
  module : {
    loaders: [
    {
     test: /\.scss$/,
      loader: 'style-loader!css?sourceMap!sass?sourceMap'
    },
    {
      test: /\.css$/,
      loader: 'style-loader!css-loader'
    },
    {
      test: /\.woff(\?v=\d+\.\d+\.\d+)?$/,
      loader: 'url?limit=10000&mimetype=application/font-woff'
    },
    {
      test: /\.woff2(\?v=\d+\.\d+\.\d+)?$/,
      loader: 'url?limit=10000&mimetype=application/font-woff'
    },
    {
      test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
      loader: 'url?limit=10000&mimetype=application/octet-stream'
    },
    {
      test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
      loader: 'file'
    },
    {
      test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
      loader: 'url?limit=10000&mimetype=image/svg+xml'
    },
    { test: /\.jpe?g$|\.gif$|\.png$/i,
      loader: 'file-loader'
    }
    ]
  }
}