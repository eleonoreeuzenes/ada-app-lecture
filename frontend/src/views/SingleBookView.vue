<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useBookStore } from '@/stores/book'
const route = useRoute()
const bookStore = useBookStore()

const userbookId = Number(route.params.id)
console.log('userbookId', userbookId)

const book = computed(() => bookStore.getUserBookById(userbookId))
console.log('book', book)
</script>
<template>
    <main class="container mx-auto px-4 py-8">
      <div v-if="!book">Livre introuvable ❌</div>
      <div v-else>
        <h1 class="text-2xl font-bold mb-4">{{ book.title }}</h1>
        <p>Auteur : {{ book.author }}</p>
        <p>Genre : {{ book.genre }}</p>
        <p>Status : {{ book.status }}</p>
        <p>Pages lues : {{ book.pages_read }} / {{ book.total_pages }}</p>
      </div>
    </main>
  </template>
  