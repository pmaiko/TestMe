import { createI18n } from 'vue3-i18n'
import router from './router'
import store from '~/store'

import uk from '~/lang/uk'

import { createApp } from 'vue'
import App from './components/App'

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
// https://vuetifyjs.com/en/components/all/
// https://habr.com/ru/articles/575050/
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import colors from 'vuetify/lib/util/colors'

window._ = require('lodash')
window.axios = require('axios')
window.axios.defaults.baseURL = '/api/'

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['Accept-Language'] = 'uk'

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    themes: {
      light: {
        dark: false,
        colors: {
          primary: colors.blue.base,
          secondary: colors.pink.base
        }
      }
    }
  }
})


const i18n = createI18n({
  locale: 'uk',
  fallbackLocale: 'zhCN',
  messages: {
    'uk': uk
  }
})
const app = createApp(App)
  .use(vuetify)
  .use(store)
  .use(router)
  .use(i18n)

app.config.globalProperties._get = _get
app.config.globalProperties.useFormattedDate = useFormattedDate

app.mount('#app')
