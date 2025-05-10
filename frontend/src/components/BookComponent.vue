<template>
  <div class="min-w-[240px] md:w-[240px] shrink-0 bg-primary-100 rounded-xl py-2 px-3 flex flex-col justify-between h-full">
    <!-- Book Details -->
    <RouterLink
      :to="`/books/${book.id}`"
      class="hover:ring-2 hover:ring-blue-400 transition"
    >
      <h3 class="font-bold text-base/5 text-tertiary-900">{{ book.title }}</h3>
      <p class="text-sm text-sm/4 text-tertiary-900">{{ book.author }}</p>
      <p class="text-sm italic text-sm/4 text-tertiary-900">{{ book.genre }}</p>
    </RouterLink>

    <!-- Progress Bar or Add Button -->
    <div class="mt-6">
      <template v-if="!isInLibrary">
        <button
          @click="$emit('addToLibrary', book)"
          class="mt-4 bg-primary-500 text-white px-4 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Ajouter à mes lectures
        </button>
      </template>
      <template v-else>
        <p class="text-sm text-tertiary-900">{{ book.pages_read }}/{{ book.total_pages }} pages</p>
        <div class="w-full bg-white rounded-full h-2 mt-1">
          <div
            class="bg-primary-500 h-2 rounded-full transition-all duration-500 ease-in-out"
            :style="{ width: calculateProgress(book) + '%' }"
          ></div>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router';

defineProps<{
  book: {
    id: number;
    title: string;
    author: string;
    genre: string;
    pages_read: number;
    total_pages: number;
  };
  isInLibrary: boolean; 
}>();

defineEmits(['addToLibrary']);
// Function to calculate the progress percentage
const calculateProgress = (book: { pages_read: number; total_pages: number }): number => {
  if (book.total_pages === 0) return 0;
  return Math.round((book.pages_read / book.total_pages) * 100);
};
</script>