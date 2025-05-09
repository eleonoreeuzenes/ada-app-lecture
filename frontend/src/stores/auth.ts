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
      console.log('Token after login:', this.token);
      console.log('response:', response.data);
      console.log('User after login:', this.user);
    },

    async updateUser(payload: { username: string; email: string; password?: string }) {
      console.log('Payload being sent:', payload);
      const response = await api.patch('/me', payload, {
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
      });
      this.user = response.data.user; // Update the user in the store
    },

    async updatePassword(payload: { old_password: string; new_password: string }) {
      console.log('Payload being sent:', payload);
      const response = await api.patch('/me/password', payload, {
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
      });
      console.log('Password update response:', response.data);

    },
    
    async logout() {

      console.log('Logging out...')
      console.log('Token:', this.token)
      await api.post('/logout', {}, {
        headers: {
          Authorization: `Bearer ${this.token}`, 
        },
      });
        this.token = null
        this.user = null
        this.isAuthenticated = false
        localStorage.removeItem('authToken')
        router.push('/login') 
        }
  },
})
