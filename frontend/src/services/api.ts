// src/services/api.ts
import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: true, 
})

const token = localStorage.getItem('authToken')
console.log('Token:', token)
if (token) {
  api.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

const apiKey = import.meta.env.VITE_API_KEY;
console.log('API Key:', apiKey)
if (apiKey) {
  api.defaults.headers.common['x-api-key'] = apiKey;
}

export default api
