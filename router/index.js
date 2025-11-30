import { createRouter, createWebHistory } from 'vue-router'
import { useQuizStore } from '../store/quizStore'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/login', component: () => import('../views/Login.vue') },
    { path: '/register', component: () => import('../views/Register.vue') },
    { 
      path: '/dashboard', 
      component: () => import('../views/Dashboard.vue'), 
      meta: { requiresAuth: true }
    },
    { 
      path: '/quiz/:id', 
      component: () => import('../views/QuizView.vue'), 
      meta: { requiresAuth: true }
    },
    { 
      path: '/resultado', 
      component: () => import('../views/ResultView.vue'), 
      meta: { requiresAuth: true }
    },
  ]
})

router.beforeEach((to, from, next) => {
  const store = useQuizStore()
  if (to.meta.requiresAuth && !store.isAuthenticated) {
    next('/login')
  } else if ((to.path === '/login' || to.path === '/register') && store.isAuthenticated) {
    next('/dashboard')
  } else {
    next()
  }
})

export default router