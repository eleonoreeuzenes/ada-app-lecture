<template>
  <section>
    <h2 class="text-xl font-semibold mb-2 px-4">{{ title }}</h2>
    <div class="flex gap-4 overflow-x-auto md:flex-wrap md:overflow-visible pl-4">
      <BookCardComponent
        v-for="book in books"
        :key="book.id"
        :book="book"
        :isInLibrary="true"
      />
    </div>
  </section>
</template>

<script setup lang="ts">
import BookCardComponent from './BookCardComponent.vue';

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
</script>