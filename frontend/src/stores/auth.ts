// src/stores/auth.ts
import { defineStore } from 'pinia'
import api from '@/services/api'
import { useRouter } from 'vue-router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as null | { id: number; username: string; email: string },
    token: null as string | null,
  }),

  actions: {
    async register(email: string, username: string, password: string) {
      const response = await api.post('/register', {
        email,
        username,
        password,
      })
      this.token = response.data.token
      this.user = response.data.user
    },

    // async login(email: string, password: string) {
    //   const response = await api.post('/login', {
    //     email,
    //     password,
    //   })
    //   this.token = response.data.token
    //   this.user = response.data.user
    // },

    // logout() {
    //   this.token = null
    //   this.user = null
    //   // (optionnel) faire une requête à /logout
    // },
  },
})
