<template>
    <main class="container mx-auto px-4 py-8">
      <h1 class="text-2xl font-bold mb-6">Ma bibliothèque</h1>
  
      <div v-if="bookStore.isLoading" class="text-gray-500">Chargement...</div>
      <div v-else-if="bookStore.error" class="text-red-500">{{ bookStore.error }}</div>
      <div v-else>
        <div v-if="bookStore.userbooks.length === 0" class="text-gray-500">Aucun livre ajouté.</div>
  
        <div v-else class="space-y-10">
          <BookSection title="À lire" :books="toReadBooks" />
          <BookSection title="En cours" :books="inProgressBooks" />
          <BookSection title="Terminés" :books="finishedBooks" />
        </div>
      </div>
    </main>
  </template>
  
  
  <script setup lang="ts">
  import { onMounted, computed } from 'vue';
  import { useBookStore } from '@/stores/book';
  import BookSection from '@/components/BookSectionComponent.vue'

  
  const bookStore = useBookStore();
  
  onMounted(async () => {
    await bookStore.fetchUserBooks();
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