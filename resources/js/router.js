import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '~/components/pages/LoginPage'
// import CabinetPage from '~/components/pages/CabinetPage'
import TestsPage from '~/components/pages/cabinet/TestsPage'
import ResultsPage from '~/components/pages/cabinet/ResultsPage'
import UsersPage from '~/components/pages/cabinet/UsersPage'
import SettingsPage from '~/components/pages/cabinet/SettingsPage'

const routes = [
  { path: '/', component: LoginPage },
  {
    path: '/cabinet',
    children: [
      {
        path: 'tests',
        name: 'tests',
        component: TestsPage
      },
      {
        path: 'results',
        name: 'results',
        component: UsersPage
      },
      {
        path: 'users',
        name: 'users',
        component: ResultsPage
      },
      {
        path: 'settings',
        name: 'settings',
        component: SettingsPage
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
