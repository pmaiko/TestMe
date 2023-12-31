module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  parserOptions: {
    parser: 'babel-eslint'
  },
  extends: [
    // https://www.npmjs.com/package/eslint-plugin-vue
    // https://www.npmjs.com/package/eslint-config-standard
    // https://www.npmjs.com/package/eslint-plugin-import
    // https://www.npmjs.com/package/eslint-plugin-n
    // https://www.npmjs.com/package/eslint-plugin-promise
    // https://www.npmjs.com/package/babel-eslint
    'plugin:vue/recommended',
    'standard',
    './.eslintrc-auto-import.json'
  ],
  plugins: [
    'vue'
  ],
  rules: {
    // js
    'vue/prop-name-casing': 0,
    'indent': ['error', 2],
    'no-console': 0,
    'prefer-const': ['error', { 'destructuring': 'all' }],
    'no-return-await': 0,
    'no-unused-expressions': 0,
    'no-trailing-spaces': 0,
    'no-multiple-empty-lines': ['error', { 'max': 2 }],
    'vue/no-multiple-template-root': 'off',
    'no-new': 0,
    'no-prototype-builtins': 0,
    'handle-callback-err': 0,
    'valid-typeof': 0,
    'dot-notation': 0,
    'quote-props': ['error', 'consistent'],

    // vue
    'vue/html-closing-bracket-newline': ['error', {
      'singleline': 'never',
      'multiline': 'always'
    }],
    // 'vue/max-attributes-per-line': [ 'error', {
    //   'singleline': 3,
    //   'multiline': {
    //     'max': 1,
    //     'allowFirstLine': false
    //   }
    // } ],
    'vue/attribute-hyphenation': 0,
    'vue/require-prop-types': 0,
    'vue/require-default-prop': 0,
    'vue/script-indent': ['error', 2, { 'baseIndent': 1 }],
    'vue/no-v-html': 0
  },
  overrides: [
    {
      'files': ['*.vue'],
      'rules': {
        'indent': 'off'
      }
    }
  ],
  globals: {
    '_get': true,
    '$t': true,
    '$vuetify': true
  }
}
