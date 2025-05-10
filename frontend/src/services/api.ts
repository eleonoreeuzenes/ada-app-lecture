// src/services/api.ts
import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: true, 
})

const token = localStorage.getItem('authToken')
if (token) {
  api.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

export default api
