var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
	entry: [
	'./build/js/app.js',
	'./build/bootstrap/js/bootstrap.js',
	'./build/scss/AdminLTE.scss',
	],
	output: {
		path: path.join(__dirname,'dist/'),
		filename: 'bundle.js'
	},
	 plugins: [
	 new ExtractTextPlugin('styles.css', {allChunks: true}),
	 new webpack.optimize.UglifyJsPlugin({minimize: true})
	 ],
	module : {
		loaders: [
		{
			test: /\.scss$/,
			loader: ExtractTextPlugin.extract('style-loader', 'css-loader!sass-loader')
		},
		{
			test: /\.css$/,
			loader: ExtractTextPlugin.extract('style-loader', 'css-loader')
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
                loader: 'file'
            }
		]
	}
}