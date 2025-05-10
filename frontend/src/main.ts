import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import '@fortawesome/fontawesome-free/css/all.min.css';
import Toast from "vue-toastification";
import type { PluginOptions } from "vue-toastification";
import "vue-toastification/dist/index.css";

import App from './App.vue'
import router from './router'

const app = createApp(App)

const options: PluginOptions = {
    // You can set your default options here
};
app.use(createPinia())
app.use(router)
app.use(Toast, options);

import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
authStore.tryAutoLogin()

app.mount('#app')
