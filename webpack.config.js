const path = require('path')
const StylelintPlugin = require('stylelint-webpack-plugin')
const stylelintFormatter = require('stylelint-formatter-pretty')
const ErrorOverlayPlugin = require('error-overlay-webpack-plugin')

module.exports = {
  resolve: {
    fallback: { 'querystring': require.resolve('querystring-es3') },
    modules: [
      // path.resolve(__dirname, 'resources/js'),
      path.resolve(__dirname, 'node_modules')
    ],
    alias: {
      '~': path.resolve(__dirname, 'resources/js/')
    }
  },
  module: {
    rules: [
      {
        test: /\.svg$/,
        use: [
          'svg-vue3-loader'
        ]
      },
      {
        enforce: 'pre',
        test: /\.(js|vue)$/,
        loader: 'eslint-loader',
        exclude: /node_modules/,
        options: {
          emitError: true,
          emitWarning: true,
          failOnError: true
        }
      },
      {
        test: /\.scss$/,
        use: [
          {
            loader: require.resolve('sass-resources-loader'),
            options: {
              resources: [
                path.resolve('./resources/js/scss/mixins/*.scss')
              ]
            }
          },
          {
            loader: '@epegzz/sass-vars-loader',
            options: {
              syntax: 'scss',
              files: [
                path.resolve(__dirname, 'resources/js/vars/colors.js'),
                path.resolve(__dirname, 'resources/js/vars/fonts.js')
              ]
            }
          }]
      }
    ]
  },
  plugins: [
    new StylelintPlugin({
      files: ['./resources/js/**/*.{css,scss,vue}'],
      configFile: 'stylelint.config.js',
      formatter: stylelintFormatter
    }),
    new ErrorOverlayPlugin()
  ]
}
