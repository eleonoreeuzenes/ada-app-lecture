<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useBookStore } from '@/stores/book'
const route = useRoute()
const bookStore = useBookStore()

const userbookId = Number(route.params.id)
console.log('userbookId', userbookId)

const book = computed(() => bookStore.getUserBookById(userbookId))
console.log('book', book)

const pagesInput = ref(0)

watch(book, (val) => {
  if (val) {
    pagesInput.value = val.pages_read 
  }
})

const markAsRead = async () => {
  if (book.value) {
    await bookStore.updateUserBook({
      id: book.value.id,
      status: 'finished',
      pages_read: book.value.total_pages,
    });
  }
};

const updateProgress = async () => {
  if (book.value) {
    if (book.value.status === 'to_read') {
      book.value.status = 'in_progress'
    }
    await bookStore.updateUserBook({
      id: book.value.id,
      status: book.value.status,
      pages_read: pagesInput.value,
    })
  }
};

const progressPercentage = computed(() => {
  if (book.value) {
    return Math.round((book.value.pages_read / book.value.total_pages) * 100);
  }
  return '0';
});
</script>
<template>
    <main class="container mx-auto px-4 py-8">
      <div v-if="!book">Livre introuvable </div>
      <div v-else>
        <h1 class="text-2xl font-bold mb-4">{{ book.title }}</h1>
        <p>Auteur : {{ book.author }}</p>
        <p>Genre : {{ book.genre }}</p>
        <p>Status : {{ book.status }}</p>
        <p>Pages lues : {{ book.pages_read }} / {{ book.total_pages }}</p>

        <!-- Progress Bar -->
      <div class="mt-4">
        <label class="block font-semibold mb-1">Progression :</label>
        <div class="w-full bg-gray-200 rounded-full h-4">
          <div
            class="bg-primary-600 h-4 rounded-full transition-all duration-500 ease-in-out"
            :style="{ width: progressPercentage + '%' }"
          ></div>
        </div>
        <p class="text-sm mt-1">{{ progressPercentage }}% lu</p>
      </div>

        <button
        v-if="book.status === 'to_read' || book.status === 'in_progress'"
        @click="markAsRead"
        class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
      >
        Marquer comme lu
      </button>
      <div v-if="book.status === 'to_read' || book.status === 'in_progress'" class="mt-4">
        <label for="pagesInput" class="block font-semibold mb-1">Mettre à jour les pages lues :</label>
        <input
          id="pagesInput"
          v-model="pagesInput"
          type="number"
          :max="book.total_pages"
          :min="0"
          class="border rounded px-2 py-1 w-24"
        />
        <button
          @click="updateProgress"
          class="ml-2 bg-secondary-300 text-gray-700 px-3 py-1 rounded hover:bg-secondary-200"
        >
          Enregistrer
        </button>
      </div>
      </div>
    </main>
  </template>
  