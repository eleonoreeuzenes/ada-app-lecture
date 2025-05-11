<template>
    <main class="container mx-auto py-8">
      <h1 class="text-2xl font-bold px-4 mb-6">Ma bibliothèque</h1>
  
      <div v-if="bookStore.isLoading" class="text-tertiary-500 px-4">Chargement...</div>
      <div v-else-if="bookStore.error" class="text-red-500 px-4">{{ bookStore.error }}</div>
      <div v-else>
        <div v-if="bookStore.userbooks.length === 0" class="text-tertiary-500 px-4 ">Aucun livre ajouté.</div>
        <div v-else class="space-y-4">
          <BookList title="À lire" :books="toReadBooks" />
          <BookList title="En cours" :books="inProgressBooks" />
          <BookList title="Terminés" :books="finishedBooks" />
        </div>
      </div>
    </main>
  </template>
  
  
  <script setup lang="ts">
  import { onMounted, computed } from 'vue';
  import { useBookStore } from '@/stores/book';
  import BookList from '@/components/BookListComponent.vue'

  const bookStore = useBookStore();
  
  onMounted(async () => {
    if (bookStore.userbooks.length === 0) {
      await bookStore.fetchUserBooks();
    }
  });

  const toReadBooks = computed(() =>
  bookStore.userbooks.filter(book => book.status === 'to_read')
    )
    const inProgressBooks = computed(() =>
    bookStore.userbooks.filter(book => book.status === 'in_progress')
    )
    const finishedBooks = computed(() =>
    bookStore.userbooks.filter(book => book.status === 'finished')
    )

  </script>