const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');

module.exports = {
  entry: {
    pfrpsg: './src/index.js',
    landing: './src/landing.js'
  },
  output: {
    filename: '[name].js',
    chunkFilename: '[name].bundle.js?h=[contenthash]',
    path: path.resolve(__dirname, 'build'),
    clean: true,
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader',
        ],
      },
      {
        test: /\.(png|svg|jpg|gif)$/,
        type: 'asset',
      },
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: [
          {
            loader: 'babel-loader',
          },
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
    new StyleLintPlugin({
      context: 'src',
      configFile: path.resolve(__dirname, './.stylelintrc.json'),
      files: ['**/*.scss'],
      fix: false,
      cache: true,
      emitErrors: true,
      failOnError: false,
    }),
    new ESLintPlugin(),
  ],
  optimization: {
    splitChunks: {
      chunks: 'all',
    },
  },
};
