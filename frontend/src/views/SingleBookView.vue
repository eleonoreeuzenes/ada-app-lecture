<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useBookStore } from '@/stores/book';
import { useToast } from 'vue-toastification';
import { useAuthStore } from '@/stores/auth';

const toast = useToast();
const route = useRoute();
const bookStore = useBookStore();
const authStore = useAuthStore();

const userbookId = Number(route.params.id);
console.log('userbookId', userbookId);

const book = computed(() => bookStore.getUserBookById(userbookId));
console.log('book', book);

const pagesInput = ref(0);

watch(book, (val) => {
  if (val) {
    pagesInput.value = val.pages_read;
  }
});

const statusTranslations: Record<string, string> = {
  to_read: 'À lire',
  in_progress: 'En cours',
  finished: 'Terminé',
};

const getTranslatedStatus = (status: string): string => {
  return statusTranslations[status] || status;
};

const markAsRead = async () => {
  if (!book.value) return;

  let UserTotalPoints = 0;
  if (authStore.user) {
    UserTotalPoints = authStore.user.total_points;
  }

  const response = await bookStore.updateUserBook({
    id: book.value.id,
    status: 'finished',
    pages_read: book.value.total_pages,
  });

  if (response?.total_points && authStore.user) {
    const pointsGained = response.total_points - UserTotalPoints;
    authStore.user.total_points = response.total_points;

    if (pointsGained > 0) {
      toast.success(`🎉 Bravo ! Tu as gagné ${pointsGained} points !`, {
        timeout: 2000,
      });
    }
  }
};

const updateProgress = async () => {
  if (book.value) {
    if (book.value.status === 'to_read') {
      book.value.status = 'in_progress';
    }
    await bookStore.updateUserBook({
      id: book.value.id,
      status: book.value.status,
      pages_read: pagesInput.value,
    });
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
  <main class="container mx-auto pb-8">
    <div v-if="!book" class="text-center px-4 text-tertiary-700">
      <p class="text-lg font-semibold mt-2">Livre introuvable</p>
    </div>
    <div v-else>
      <!-- Book Details -->
      <div class="bg-primary-100 rounded-xl p-6 mb-6">
        <h1 class="text-3xl font-bold text-tertiary-900 mb-4">{{ book.title }}</h1>
        <p class="text-lg text-tertiary-900 mb-2"><strong>Auteur :</strong> {{ book.author }}</p>
        <p class="text-lg text-tertiary-900 mb-2"><strong>Genre :</strong> {{ book.genre }}</p>
        <p class="text-lg text-tertiary-900 mb-2">
          <span
            :class="{
              'text-green-600': book.status === 'finished',
              'text-yellow-600': book.status === 'in_progress',
              'text-tertiary-600': book.status === 'to_read',
            }"
          >
           {{ getTranslatedStatus(book.status) }}
          </span>
        </p>
      </div>

      <!-- Progress Bar -->
      <div class="p-6 mb-6">
        <div class="w-full bg-gray-200 rounded-full h-4">
          <div
            class="bg-primary-500 h-4 rounded-full transition-all duration-500 ease-in-out"
            :style="{ width: progressPercentage + '%' }"
          ></div>
        </div>
        <p class="text-sm mt-2 text-tertiary-600">{{ progressPercentage }}% lu,
          <strong>Pages lues :</strong> {{ book.pages_read }}/{{ book.total_pages }}
        </p>
      </div>

      <!-- Actions -->
      <div class="flex flex-col px-4 gap-4">
        <!-- Mark as Read Button -->
        <button
          v-if="book.status === 'to_read' || book.status === 'in_progress'"
          @click="markAsRead"
          class="w-full bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Marquer comme lu
        </button>

        <!-- Update Progress -->
        <div v-if="book.status === 'to_read' || book.status === 'in_progress'" class="p-6">
          <label for="pagesInput" class="block font-semibold text-tertiary-700 mb-2">Mettre à jour les pages lues :</label>
          <div class="flex items-center gap-2">
            <input
              id="pagesInput"
              v-model="pagesInput"
              type="number"
              :max="book.total_pages"
              :min="0"
              class="border rounded px-3 py-2 w-24 focus:ring-primary-500 focus:border-primary-500"
            />
            <button
              @click="updateProgress"
              class="bg-secondary-300 text-tertiary-900 px-4 py-2 rounded-md hover:bg-secondary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400"
            >
              Enregistrer
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>