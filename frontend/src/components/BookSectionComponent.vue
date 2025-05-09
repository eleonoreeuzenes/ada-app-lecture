<template>
    <section>
      <h2 class="text-xl font-semibold mb-3">{{ title }}</h2>
      <div
        class="flex gap-4 overflow-x-auto md:flex-wrap md:overflow-visible"
      >
        <div
          v-for="book in books"
          :key="book.id"
          class="min-w-[240px] md:w-[240px] shrink-0 bg-white shadow p-4 rounded border"
        >
        <RouterLink
        :to="`/books/${book.id}`"
        class=" hover:ring-2 hover:ring-blue-400 transition"
        >
        <h3 class="font-bold text-lg">{{ book.title }}</h3>
        <p class="text-sm text-gray-600">Auteur : {{ book.author }}</p>
        <p class="text-sm text-gray-600">Genre : {{ book.genre }}</p>
        <p class="text-sm text-gray-600">Pages : {{ book.pages_read }} / {{ book.total_pages }}</p>
        </RouterLink>
        </div>
        
      </div>
    </section>
  </template>
  
  <script setup lang="ts">
  import { RouterLink } from 'vue-router'

  defineProps<{
    title: string
    books: any[]
  }>()
  </script>
  