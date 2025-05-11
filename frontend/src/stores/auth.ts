// src/stores/auth.ts
import { defineStore } from 'pinia'
import api from '@/services/api'
import router from '@/router';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as null | { id: number; username: string; email: string, total_points: number }, 
    token: localStorage.getItem('authToken'), 
    isAuthenticated: !!localStorage.getItem('authToken'), 
  }),

  actions: {

    async fetchUser() {
      if (!this.token) return
    
      try {
        const response = await api.get('/me')
        this.user = response.data
        this.isAuthenticated = true
      } catch (error) {
        console.error('Erreur fetchUser:', error)
        this.logout()
      }
    },
    
    async tryAutoLogin() {
      if (this.token && !this.user) {
        await this.fetchUser()
      }
    },
    
    async register(email: string, username: string, password: string) {
        
      const response = await api.post('/register', {
        email,
        username,
        password,
      })
      this.token = response.data.access_token
    },

    async login(email: string, password: string) {
      const response = await api.post('/login', {
        email,
        password,
      })
      this.token = response.data.access_token
      this.user = response.data.user
      this.isAuthenticated = true
      localStorage.setItem('authToken', response.data.access_token)
    },

    async updateUser(payload: { username: string; email: string; password?: string }) {
      const response = await api.patch('/me', payload);
      this.user = response.data.user;
    },

    async updatePassword(payload: { old_password: string; new_password: string }) {
      const response = await api.patch('/me/password', payload);
    },
    
    async logout() {
      await api.post('/logout');
        this.token = null
        this.user = null
        this.isAuthenticated = false
        localStorage.removeItem('authToken')
        router.push('/login') 
        },

      async deleteAccount(password: string) {
          const token = this.token
          if (!token) throw new Error('Not authenticated')
        
          await api.delete('/me', {
            data: { password }, 
          })
        
          this.token = null
          this.user = null
          this.isAuthenticated = false
          localStorage.removeItem('authToken')
          router.push('/register')
        },
  },

  getters: {
    userLevel: (state) => {
      const points = state.user?.total_points || 0
      if (points <= 50) return 'Débutant'
      if (points <= 150) return 'Amateur'
      if (points <= 300) return 'Confirmé'
      return 'Expert'
    }
  }

})
