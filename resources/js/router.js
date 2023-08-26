import { createRouter, createWebHistory } from 'vue-router'

import LoginPage from '~/components/pages/LoginPage'
// import CabinetPage from '~/components/pages/CabinetPage'
import TestsPage from '~/components/pages/cabinet/TestsPage'
import ResultsPage from '~/components/pages/cabinet/ResultsPage'
import UsersPage from '~/components/pages/cabinet/UsersPage'
import SettingsPage from '~/components/pages/cabinet/SettingsPage'

// test
import TestCreatePage from '~/components/pages/cabinet/test/TestCreatePage'
import TestUpdatePage from '~/components/pages/cabinet/test/TestUpdatePage'
import QuestionCreate from '~/components/pages/cabinet/test/QuestionCreate'
import ErrorForbidden from '~/components/ErrorForbidden'
import ErrorNotFound from '~/components/ErrorNotFound'

const routes = [
  {
    path: '/',
    name: 'login',
    component: LoginPage
  },
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
        component: ResultsPage,
        meta: { admin: true }
      },
      {
        path: 'settings',
        name: 'settings',
        component: SettingsPage
      }
    ]
  },
  {
    path: '/cabinet/test',
    children: [
      {
        path: 'create',
        name: 'test-create',
        component: TestCreatePage,
        meta: { admin: true }
      },
      {
        path: 'update/:test_id',
        name: 'test-update',
        component: TestUpdatePage,
        meta: { admin: true }
      },
      {
        path: 'update/:test_id/question/create',
        name: 'test-update-question-create',
        component: QuestionCreate,
        meta: { admin: true }
      }
    ]
  },
  {
    path: '/forbidden',
    name: 'forbidden',
    component: ErrorForbidden
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'error',
    component: ErrorNotFound
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior (to, from, savedPosition) {
    return { left: 0, top: 0 }
  },
  linkActiveClass: 'active'
})

window.router = router

export default router
