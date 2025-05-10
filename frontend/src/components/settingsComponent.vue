<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';

const isSettingsOpen = ref(false);
const isPasswordChangeOpen = ref(false);
const authStore = useAuthStore();
const message = ref(''); 
const messageType = ref(''); 

const toggleSettings = () => {
  isSettingsOpen.value = !isSettingsOpen.value; 
  message.value = ''; 
  messageType.value = '';
};

const togglePasswordChange = () => {
  isPasswordChangeOpen.value = !isPasswordChangeOpen.value; // Toggle the password change form
};


const username = ref(authStore.user?.username || '');
const email = ref(authStore.user?.email || '');
const oldPassword = ref('');
const newPassword = ref('');

const handleUpdate = async () => {
  try {
    // Call an action in the auth store to update the user information
    await authStore.updateUser({ username: username.value, email: email.value});
    message.value = 'Informations mises à jour avec succès.'; 
    messageType.value = 'success'; 
    setTimeout(() => {
      message.value = ''; 
      messageType.value = ''; 
    }, 3000);
  } catch (error) {
    message.value = 'Une erreur est survenue lors de la mise à jour.';
    messageType.value = 'error';
  }
};

const handleChangePassword = async () => {
  try {
    // Call an action in the auth store to change the password
    await authStore.updatePassword({ old_password: oldPassword.value, new_password: newPassword.value });
    message.value = 'Mot de passe mis à jour avec succès.';
    messageType.value = 'success';
    oldPassword.value = '';
    newPassword.value = '';
    isPasswordChangeOpen.value = false; // Close the password change form
    setTimeout(() => {
      message.value = '';
      messageType.value = '';
    }, 3000);
  } catch (error) {
    message.value = 'Une erreur est survenue lors de la mise à jour du mot de passe.';
    messageType.value = 'error';
  }
};

const handleLogout = () => {
  authStore.logout(); // Trigger the logout action
};

const showDeleteConfirm = ref(false);
const deletePassword = ref('');
const deleteError = ref('');

const confirmDelete = () => {
  showDeleteConfirm.value = true;
  deletePassword.value = '';
  deleteError.value = '';
};

const deleteAccount = async () => {
  try {
    await authStore.deleteAccount(deletePassword.value);
  } catch (error: any) {
    deleteError.value = error?.response?.data?.message || 'Erreur lors de la suppression';
  }
};
</script>

<template>
  <!-- Settings Icon -->
  <div class="relative">
    <button @click="toggleSettings" class="text-secondary-600 hover:text-gray-900">
      <i class="fas fa-cog text-3xl lg:text-2xl"></i>
    </button>

    <!-- Full-Screen Overlay -->
    <div
      v-if="isSettingsOpen"
      class="fixed inset-0 flex items-center justify-center z-50"
    >
      <div class="bg-white w-full h-full flex flex-col items-center justify-center ">

        <h2 class="text-xl font-bold">Mettre à jour les informations</h2>

        <!-- Update Form -->
        <form @submit.prevent="handleUpdate" class="w-full max-w-sm space-y-4 p-4">
          <!-- Username Input -->
          <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
            <input
              id="username"
              type="text"
              v-model="username"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>

          <!-- Email Input -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              id="email"
              type="email"
              v-model="email"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-500 hover:bg-primary-600"
            >
              Mettre à jour
            </button>
          </div>
        </form>
        <!-- Message -->
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
        
        <!-- Change Password Button -->
        <button
          @click="togglePasswordChange"
          class="w-full max-w-sm space-y-4 p-4 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 mt-4"
        >
          Changer de mot de passe
        </button>

        <!-- Password Change Form -->
        <div v-if="isPasswordChangeOpen" class="w-full max-w-sm space-y-4 p-4">
          <div>
            <label for="oldPassword" class="block text-sm font-medium text-gray-700">Ancien mot de passe</label>
            <input
              id="oldPassword"
              type="password"
              v-model="oldPassword"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div>
            <label for="newPassword" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
            <input
              id="newPassword"
              type="password"
              v-model="newPassword"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <button
            @click="handleChangePassword"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-500 hover:bg-primary-600"
          >
            Mettre à jour le mot de passe
          </button>
        </div>

        <button
          @click="handleLogout"
          class="text-primary-800 hover:text-primary-600 text-lg font-bold mt-2"
        >
          Se déconnecter
        </button>
        <button
          @click="confirmDelete"
          class="text-red-600 font-bold mt-6 hover:underline"
        >
          Supprimer mon compte
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
  <div
  v-if="showDeleteConfirm"
  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
>
  <div class="bg-white p-6 rounded shadow-md text-center max-w-sm w-full">
    <h3 class="text-lg font-semibold mb-2 text-red-600">⚠️ Supprimer votre compte</h3>
    <p class="text-sm text-gray-600 mb-4">Cette action est irréversible. Entrez votre mot de passe pour confirmer.</p>

    <input
      v-model="deletePassword"
      type="password"
      placeholder="Mot de passe"
      class="w-full mb-4 px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
    />

    <div class="text-red-600 text-sm mb-2" v-if="deleteError">{{ deleteError }}</div>

    <div class="flex justify-center gap-4">
      <button
        @click="deleteAccount"
        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
      >
        Supprimer
      </button>
      <button
        @click="showDeleteConfirm = false"
        class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
      >
        Annuler
      </button>
    </div>
  </div>
</div>

</template>
