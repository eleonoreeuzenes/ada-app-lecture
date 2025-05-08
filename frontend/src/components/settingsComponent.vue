<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';

const isSettingsOpen = ref(false); // State to toggle the settings overlay
const authStore = useAuthStore(); // Access the auth store

const toggleSettings = () => {
  isSettingsOpen.value = !isSettingsOpen.value; // Toggle the overlay
};

const handleLogout = () => {
  authStore.logout(); // Trigger the logout action
};
</script>

<template>
  <!-- Settings Icon -->
  <div class="relative">
    <button @click="toggleSettings" class="text-gray-700 hover:text-gray-900">
      <i class="fas fa-cog text-2xl"></i>
    </button>

    <!-- Full-Screen Overlay -->
    <div
      v-if="isSettingsOpen"
      class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50"
    >
      <div class="bg-white w-full h-full flex flex-col items-center justify-center space-y-4">
        <button
          @click="handleLogout"
          class="text-primary-800 hover:text-primary-600 text-lg font-bold"
        >
          Se déconnecter
        </button>
        <button
          @click="toggleSettings"
          class="text-primary-600 text-2xl top-4 right-4 absolute"
        >
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any additional styles here */
</style>