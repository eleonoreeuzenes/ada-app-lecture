<script setup lang="ts">
import { RouterLink, RouterView, useRoute } from 'vue-router'
const route = useRoute(); 
</script>

<template>
  <div v-if="!['register', 'login'].includes((route.name as string) ?? '')" class="hidden lg:flex sticky top-0 bg-white border-b border-gray-200 py-2 z-50">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center">
        <div class="flex items-center">
          <img src="@/assets/booklad_long.svg" alt="Logo" class="h-8">
        </div>

        <div class="md:flex space-x-8">
          <router-link to="/" class="flex items-center py-1 px-2 text-primary-900" :class="{ 'bg-primary-200 rounded ': $route.path === '/' }">
            <i class="fas fa-book mr-1"></i>
            <span>Mes livres</span>
          </router-link>
          <router-link to="/add" class="flex items-center py-1 px-2 text-primary-900" :class="{ 'bg-primary-200 rounded ': $route.path === '/add' }">
            <i class="fas fa-plus mr-1"></i>
            <span>Ajouter un livre</span>
          </router-link>
          <router-link to="/badge" class="flex items-center py-1 px-2 text-primary-900" :class="{ 'bg-primary-200 rounded ': $route.path === '/badge' }">
            <i class="fas fa-trophy mr-1"></i>
            <span>Mes badges</span>
          </router-link>
          <router-link to="/account" class="flex items-center py-1 px-2 text-primary-900" :class="{ 'bg-primary-200 rounded ': $route.path === '/account' }">
            <i class="fas fa-user mr-1"></i>
            <span>Mon profil</span>
          </router-link>
        </div>
    </div>
  </div>
</div>
  <RouterView />
  <div v-if="!['register', 'login'].includes((route.name as string) ?? '')" class="fixed lg:hidden bottom-0 left-0 right-0 bg-tertiary-950 border-t border-gray-200 py-1">
    <div class="flex justify-around">
      <router-link
        to="/"
        class="flex flex-col items-center px-1 py-2"
        :class="$route.path === '/' ? 'text-tertiary-50' : 'text-tertiary-500'"
      >
        <i class="fas fa-book text-xl"></i>
        <span class="text-xs mt-1">Mes livres</span>
      </router-link>
      <router-link
        to="/add"
        class="flex flex-col items-center px-1 py-2"
        :class="$route.path === '/add' ? 'text-tertiary-50' : 'text-tertiary-500'"
      >
        <i class="fas fa-plus text-xl"></i>
        <span class="text-xs mt-1">Ajouter un livre</span>
      </router-link>
      <router-link
        to="/badge"
        class="flex flex-col items-center px-1 py-2"
        :class="$route.path === '/badge' ? 'text-tertiary-50' : 'text-tertiary-500'"
      >
        <i class="fas fa-trophy text-xl"></i>
        <span class="text-xs mt-1">Mes badges</span>
      </router-link>
      <router-link
        to="/account"
        class="flex flex-col items-center px-1 py-2"
        :class="$route.path === '/account' ? 'text-tertiary-50' : 'text-tertiary-500'"
      >
        <i class="fas fa-user text-xl"></i>
        <span class="text-xs mt-1">Mon profil</span>
      </router-link>
    </div>
  </div>
</template>

