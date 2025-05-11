<script setup lang="ts">
import { ref, computed, onBeforeUnmount} from 'vue';
import { useBookStore } from '@/stores/book';
import { useRouter } from 'vue-router';
import BookCardComponent from '@/components/BookCardComponent.vue';

const searchQuery = ref('');
const bookStore = useBookStore();
const router = useRouter();

const userBooks = computed(() => bookStore.userbooks);

const searchBooks = async () => {
  await bookStore.searchBooks(searchQuery.value);
};

const goToCreateBook = () => {
  router.push({ name: 'createbook' }); 
};

const addToLibrary = async (book: {
  id: number;
  title: string;
  author: string;
  total_pages: number;
  genre: { id: number; name: string };
}) => {
  await bookStore.addBookToLibrary({
    book_id: book.id,
    status: 'to_read',
    pages_read: 0,
  });
};

const isInLibrary = (bookId: number) => {
  return userBooks.value.some((userBook) => userBook.id === bookId);
};

onBeforeUnmount(() => {
  searchQuery.value = '';
  bookStore.books = [];
});
</script>

<template>
  <main>
    <section class="w-full bg-secondary-200 py-6 rounded-b-3xl">
      <div class="container lg:w-1/3 mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Rechercher un livre </h2>
        <div class="relative w-full max-w-lg">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Rechercher un livre..."
            class="w-full px-4 py-2 pl-10 border bg-white border-gray-300 rounded-full shadow-sm focus:ring-primary-500 focus:border-primary-500"
            @keyup.enter="searchBooks"
          />
          <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
      </div>
    </section>

    <section class="container mx-auto lg:w-1/3 lg: px-4 mt-4">
      <h3 class="text-lg font-bold text-gray-800 mb-4">Résultats de recherche</h3>
      <div v-if="bookStore.isLoading" class="text-tertiary-500">Chargement...</div>
      <div v-else-if="bookStore.error" class="text-red-500">{{ bookStore.error }}</div>
      <div v-else>
        <div v-if="bookStore.books.length === 0" class="text-tertiary-500">Aucun résultat</div>
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <BookCardComponent
            v-for="book in bookStore.books"
            :key="book.id"
            :book="{ ...book, genre: book.genre.name, pages_read: 0 }"
            :isInLibrary="isInLibrary(book.id)" 
            @addToLibrary="addToLibrary"
          />
        </div>
      </div>
      <div class="mt-4">
        <button
          @click="goToCreateBook"
          class="bg-primary-500 w-full text-white px-4 py-2 rounded-md hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Créer un livre
        </button>
      </div>
    </section>
  </main>
</template>