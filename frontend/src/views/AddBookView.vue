<script setup lang="ts">
import { ref } from 'vue';
import { useBookStore } from '@/stores/book';
import { useRouter } from 'vue-router';

const searchQuery = ref('');
const bookStore = useBookStore();
const router = useRouter();

const searchBooks = async () => {
  await bookStore.searchBooks(searchQuery.value);
};

const goToCreateBook = () => {
  router.push({ name: 'createbook' }); // Redirect to the "Create Book" view
};
</script>

<template>
  <main>
    <!-- Full-Width Section -->
    <section class="w-full bg-secondary-300 py-8 rounded-b-3xl">
      <div class="container mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-800 mb-2">Rechercher un livre</h2>
        <!-- Search Bar -->
        <div class="relative w-full max-w-lg">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Rechercher un livre..."
            class="w-full px-4 py-2 pl-10 border bg-white border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
            @keyup.enter="searchBooks"
          />
          <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
      </div>
    </section>

    <section class="container mx-auto px-4 mt-8">
      <h3 class="text-lg font-bold text-gray-800 mb-4">Résultats de recherche</h3>
      <div v-if="bookStore.isLoading" class="text-gray-500">Chargement...</div>
      <div v-else-if="bookStore.error" class="text-red-500">{{ bookStore.error }}</div>
      <div v-else>
        <!-- Message if no results -->
        <div v-if="bookStore.books.length === 0" class="text-gray-500">Aucun résultat</div>

        <!-- List of books -->
        <ul v-else>
          <li
            v-for="book in bookStore.books"
            :key="book.id"
            class="border-b py-2"
          >
            <h4 class="font-bold">{{ book.title }}</h4>
            <p>Auteur : {{ book.author }}</p>
            <p>Pages : {{ book.total_pages }}</p>
            <p>Genre : {{ book.genre.name }}</p>
          </li>
        </ul>
      </div>

      <!-- Button to create a new book -->
      <div class="mt-4">
        <button
          @click="goToCreateBook"
          class="bg-primary-500 w-full text-white px-4 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Créer un livre
        </button>
      </div>
    </section>
  </main>
</template>