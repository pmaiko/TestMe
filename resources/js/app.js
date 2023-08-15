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

require('./bootstrap')
const app = createApp(App).use(vuetify)

app.mount('#app')
