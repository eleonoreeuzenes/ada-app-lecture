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
        <!-- Progress Bar -->
        <div class="mt-2">
          <div class="w-full bg-gray-200 rounded-full h-4">
            <div
              class="bg-primary-600 h-4 rounded-full transition-all duration-500 ease-in-out"
              :style="{ width: calculateProgress(book) + '%' }"
            ></div>
          </div>
          <p class="text-sm mt-1 text-gray-600">{{ calculateProgress(book) }}% lu</p>
        </div>
        </div>
        
      </div>
    </section>
  </template>
  
  <script setup lang="ts">
  import { RouterLink } from 'vue-router';
  
  defineProps<{
    title: string;
    books: Array<{
      id: number;
      title: string;
      author: string;
      genre: string;
      pages_read: number;
      total_pages: number;
    }>;
  }>();
  
  // Fonction pour calculer le pourcentage de progression
  const calculateProgress = (book: {
    pages_read: number;
    total_pages: number;
  }): number => {
    if (book.total_pages === 0) return 0;
    return Math.round((book.pages_read / book.total_pages) * 100);
  };
  </script>
  