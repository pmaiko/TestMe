import { createRouter, createWebHistory } from 'vue-router'

import LoginPage from '~/components/pages/LoginPage'
// import CabinetPage from '~/components/pages/CabinetPage'
import TestsPage from '~/components/pages/cabinet/TestsPage'
import ResultsPage from '~/components/pages/cabinet/results/ResultsPage'
import UsersPage from '~/components/pages/cabinet/UsersPage'
import SettingsPage from '~/components/pages/cabinet/SettingsPage'

// test
import TestCreatePage from '~/components/pages/cabinet/TestCreatePage'
import TestUpdatePage from '~/components/pages/cabinet/TestUpdatePage'
import QuestionCreatePage from '~/components/pages/cabinet/QuestionCreatePage'
import QuestionUpdatePage from '~/components/pages/cabinet/QuestionUpdatePage'
import TestingPage from '~/components/pages/cabinet/TestingPage'
import ErrorForbidden from '~/components/ErrorForbidden'
import ErrorNotFound from '~/components/ErrorNotFound'

// result
import ResultsAttemptsPage from '~/components/pages/cabinet/results/ResultsAttemptsPage'
import ResultsAttemptPage from '~/components/pages/cabinet/results/ResultsAttemptPage'

// favorites
import FavoritesPage from '~/components/pages/cabinet/favorites/FavoritesPage.vue'

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
        children: [
          {
            path: '',
            name: 'tests',
            component: TestsPage
          },
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
            component: QuestionCreatePage,
            meta: { admin: true }
          },
          {
            path: 'update/:test_id/question/update/:question_id',
            name: 'test-update-question-update',
            component: QuestionUpdatePage,
            meta: { admin: true }
          },
          {
            path: 'testing/:test_id',
            name: 'testing',
            component: TestingPage
          }
        ]
      },
      {
        path: 'results',
        children: [
          {
            path: '',
            name: 'results',
            component: ResultsPage
          },
          {
            path: 'test/:testId',
            name: 'results-test',
            component: ResultsAttemptsPage
          },
          {
            path: 'test/:testId/:attemptId',
            name: 'results-test-attempt',
            component: ResultsAttemptPage
          }
        ]
      },
      {
        path: 'favorites',
        children: [
          {
            path: '',
            name: 'favorites',
            component: FavoritesPage
          }
        ]
      },
      {
        path: 'users',
        name: 'users',
        component: UsersPage,
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
