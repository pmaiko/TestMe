import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '~/components/pages/LoginPage'
import CabinetPage from '~/components/pages/CabinetPage'

const routes = [
  { path: '/', component: LoginPage },
  { path: '/cabinet', component: CabinetPage }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
