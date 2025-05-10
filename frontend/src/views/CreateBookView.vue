<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import { useBookStore } from '@/stores/book';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const router = useRouter();
const bookStore = useBookStore();

// Formulaire pour créer un livre
const form = ref({
  title: '',
  author: '',
  total_pages: 0,
  genre_id: 0,
  status: '',
  pages_read: 0,
});

const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

const createBook = async () => {
  try {
    await bookStore.createBook(form.value);
    message.value = 'Livre créé avec succès !';
    messageType.value = 'success';
    router.push({ name: 'home' }); 
  } catch (error) {
    message.value = bookStore.error || 'Une erreur est survenue lors de la création du livre.';
    messageType.value = 'error';
  }
};

const showPagesRead = computed(() => form.value.status === 'in_progress');

watch(
  () => form.value.status,
  (newStatus) => {
    if (newStatus === 'finished') {
      form.value.pages_read = form.value.total_pages; // Si terminé, pages lues = total pages
    } else if (newStatus !== 'in_progress') {
      form.value.pages_read = 0; // Réinitialiser si le statut n'est pas "En cours"
    }
  }
);

onMounted(async () => {
  if (bookStore.genres.length === 0) {
    await bookStore.fetchGenres();
  }
});
</script>

<template>
  <main class="container mx-auto px-4 py-8">
    <!-- Message de succès ou d'erreur -->
    <div
      v-if="message"
      :class="{
        'bg-green-100 text-green-800': messageType === 'success',
        'bg-red-100 text-red-800': messageType === 'error',
      }"
      class="p-3 rounded-md mb-4 text-center"
    >
      {{ message }}
    </div>

    <h1 class="text-2xl font-bold mb-4">Créer un nouveau livre</h1>
    <form @submit.prevent="createBook" class="space-y-4">
      <!-- Titre -->
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
        <input
          id="title"
          type="text"
          v-model="form.title"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
          required
        />
      </div>

      <!-- Auteur -->
      <div>
        <label for="author" class="block text-sm font-medium text-gray-700">Auteur</label>
        <input
          id="author"
          type="text"
          v-model="form.author"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
          required
        />
      </div>

      <!-- Nombre total de pages -->
      <div>
        <label for="total_pages" class="block text-sm font-medium text-gray-700">Nombre total de pages</label>
        <input
          id="total_pages"
          type="number"
          v-model.number="form.total_pages"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
          required
        />
      </div>

      <!-- Genre -->
      <div>
        <label for="genre_id" class="block text-sm font-medium text-gray-700">Genre</label>
        <select
          id="genre_id"
          v-model.number="form.genre_id"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
          required
        >
          <option value="" disabled>Choisir un genre</option>
          <option v-for="genre in bookStore.genres" :key="genre.id" :value="genre.id">
            {{ genre.name }}
          </option>
        </select>
      </div>

      <!-- Statut -->
      <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
        <select
          id="status"
          v-model="form.status"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
          required
        >
          <option value="" disabled>Choisir un statut</option>
          <option value="to_read">Non commencé</option>
          <option value="in_progress">En cours</option>
          <option value="finished">Terminé</option>
        </select>
      </div>

      <!-- Pages lues (affiché uniquement si le statut est "En cours") -->
      <div v-if="showPagesRead">
        <label for="pages_read" class="block text-sm font-medium text-gray-700">Pages lues</label>
        <input
          id="pages_read"
          type="number"
          v-model.number="form.pages_read"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
        />
      </div>
      <div>
        <button
          type="submit"
          class="w-full bg-primary-500 text-white py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Créer le livre
        </button>
      </div>
    </form>
  </main>
</template>