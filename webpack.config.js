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
    require('unplugin-auto-import/webpack').default({
      imports: [
        // presets
        'vue',
        'vuex',
        'vue-router',
        // custom
        // {
        //   '@vueuse/core': [
        //     // named imports
        //     'useMouse', // import { useMouse } from '@vueuse/core',
        //     // alias
        //     ['useFetch', 'useMyFetch'] // import { useFetch as useMyFetch } from '@vueuse/core',
        //   ],
        //   'axios': [
        //     // default imports
        //     ['default', 'axios'] // import { default as axios } from 'axios',
        //   ],
        //   '[package-name]': [
        //     '[import-names]',
        //     // alias
        //     ['[from]', '[alias]']
        //   ]
        // },
        // example type import
        {
          from: 'vue-router',
          imports: ['RouteLocationRaw'],
          type: true
        }
      ],
      // Enable auto import by filename for default module exports under directories
      defaultExportByFilename: false,

      dirs: [
        './resources/js/composables'
      ],

      dts: './auto-imports.d.ts',
      // see https://github.com/unjs/unimport/pull/15 and https://github.com/unjs/unimport/pull/72
      vueTemplate: false,

      // Custom resolvers, compatible with `unplugin-vue-components`
      // see https://github.com/antfu/unplugin-auto-import/pull/23/
      resolvers: [
        /* ... */
      ],

      // Inject the imports at the end of other imports
      injectAtEnd: true,

      // Generate corresponding .eslintrc-auto-import.json file.
      // eslint globals Docs - https://eslint.org/docs/user-guide/configuring/language-options#specifying-globals
      eslintrc: {
        enabled: true, // Default `false`
        filepath: './.eslintrc-auto-import.json', // Default `./.eslintrc-auto-import.json`
        globalsPropValue: true // Default `true`, (true | false | 'readonly' | 'readable' | 'writable' | 'writeable')
      }
    }),
    new StylelintPlugin({
      files: ['./resources/js/**/*.{css,scss,vue}'],
      configFile: 'stylelint.config.js',
      formatter: stylelintFormatter
    }),
    new ErrorOverlayPlugin()
  ]
}
