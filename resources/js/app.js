import './bootstrap'
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

const vuetify = createVuetify({
  components,
  directives
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

await store.dispatch('auth/checkLogged')
router.beforeEach(async (to, from) => {
  const logged = store.getters['auth/logged']
  if (!logged && to.name !== 'login') {
    return {
      name: 'login'
    }
  } if (logged && to.name === 'login') {
    return {
      name: 'tests'
    }
  }
})

app.mount('#app')
