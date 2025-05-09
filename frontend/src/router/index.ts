import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '@/views/RegisterView.vue'
import LoginView from '../views/LoginView.vue'
import { useAuthStore } from '@/stores/auth'
import AccountView from '@/views/AccountView.vue'
import AddBookView from '@/views/AddBookView.vue'
import CreateBookView from '@/views/CreateBookView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/account',
      name: 'account',
      component: AccountView,
    },
    {
      path: '/add',
      name: 'addbook',
      component: AddBookView,
    },
    {
      path: '/create-book',
      name: 'createbook',
      component: CreateBookView,
    },
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (authStore.isAuthenticated && (to.name === 'register' || to.name === 'login')) {
    next({ name: 'home' })
  }
  else if (!authStore.isAuthenticated && (to.name !== 'register' && to.name !== 'login')) {
    next({ name: 'register' })
  } else {
    next() 
  }
})


export default router
