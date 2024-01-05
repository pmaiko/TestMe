import { createStore } from 'vuex'

import auth from './auth'
import favorites from './favorites'

const store = createStore({
  modules: {
    auth,
    favorites
  }
})

export default store
