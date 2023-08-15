const mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const webpackConfig = require('./webpack.config.js')

// const { CleanWebpackPlugin } = require('clean-webpack-plugin')

Mix.listen('configReady', function (config) {
  const rules = config.module.rules
  const targetRegex = /(\.(png|jpe?g|gif|webp|avif)$|^((?!font).)*\.svg$)/

  for (const rule of rules) {
    if (rule.test.toString() == targetRegex.toString()) {
      rule.exclude = /\.svg$/
      break
    }
  }
})

mix
  // .setPublicPath('public')
  .js('resources/js/app.js', 'public/js')
  .vue({ version: 3, extractStyles: true })
  .autoload({
    'lodash/get': ['_get']
  })
  .version()
  .webpackConfig(webpackConfig)
  .sass(
    'resources/js/scss/app.scss',
    'public/css'
  )
  .sourceMaps(false, 'source-map')
  .options({
    resourceRoot: '../',
    processCssUrls: true,
    postCss: [
      // require('autoprefixer')(),
      // require('postcss-css-variables')({
      //   silent: true
      // }),
      // require('postcss-import')()
      // require('postcss-loader')()
      // require('resolve-url-loader')()
    ]
  })
  .webpackConfig({
    plugins: [
      // new CleanWebpackPlugin()
    ]
  })


if (!mix.inProduction()) {
  mix.webpackConfig({
    devtool: 'inline-source-map'
  })
}

mix.copy('node_modules/@mdi/font/fonts/', 'public/fonts/')

if (mix.inProduction()) {
  mix.version()
}
