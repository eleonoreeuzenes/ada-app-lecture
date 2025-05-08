<!-- src/views/RegisterView.vue -->
<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div>
          <img class="mx-auto w-36" src="@/assets/booklad.svg" alt="Logo">
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Prêt à lire ?
          </h2>
            <p class=" text-center text-lg text-gray-900">
                Inscris-toi et plonge dans tes livres.
            </p>
        </div>
        <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
  <div class="rounded-md shadow-sm space-y-4">
    <!-- Email Field -->
    <div>
      <label for="email" class="sr-only">Email</label>
      <input
        id="email"
        name="email"
        type="email"
        autocomplete="email"
        required
        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        placeholder="Email"
        v-model="email"
      />
    </div>

    <!-- Username Field -->
    <div>
      <label for="username" class="sr-only">Nom d'utilisateur</label>
      <input
        id="username"
        name="username"
        type="text"
        autocomplete="username"
        required
        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        placeholder="Nom d'utilisateur"
        v-model="username"
      />
    </div>

    <!-- Password Field -->
    <div>
      <label for="password" class="sr-only">Mot de passe</label>
      <input
        id="password"
        name="password"
        type="password"
        autocomplete="new-password"
        required
        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        placeholder="Mot de passe"
        v-model="password"
      />
    </div>

    <!-- Password Confirmation Field -->
    <div>
      <label for="password-confirm" class="sr-only">Confirmer le mot de passe</label>
      <input
        id="password-confirm"
        name="password-confirm"
        type="password"
        autocomplete="new-password"
        required
        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        placeholder="Confirmer le mot de passe"
        v-model="passwordConfirm"
      />
    </div>
  </div>

  <!-- Error Message -->
  <div v-if="error" class="bg-red-100 text-red-700 text-sm text-center p-3 rounded-md mb-4">
    {{ error }}
  </div>

  <!-- Submit Button -->
  <div>
    <button
      type="submit"
      :disabled="isSubmitting"
      class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <span v-if="isSubmitting" class="loader mr-2"></span>
      S'inscrire
    </button>
  </div>
</form>
<p class=" text-center text-lg text-gray-900">
               Déjà un compte ? <router-link to="/login" class="text-primary-600 font-bold hover:text-primary-800">Connecte-toi</router-link>
            </p>
      </div>
    </div>
  </template>
  
 <script lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
export default {
  setup() {
    const email = ref('');
    const username = ref('');
    const password = ref('');
    const passwordConfirm = ref('');
    const error = ref('');
    const isSubmitting = ref(false);
    const authStore = useAuthStore();
    const router = useRouter();

    const passwordRegex = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*$/;

    const handleRegister = async () => {
      if (isSubmitting.value) return;
      isSubmitting.value = true;

      if (!email.value || !username.value || !password.value || !passwordConfirm.value) {
        error.value = 'Tous les champs sont obligatoires.';
        isSubmitting.value = false;
        return;
      }

      if (password.value !== passwordConfirm.value) {
        error.value = 'Les mots de passe ne correspondent pas.';
        isSubmitting.value = false;
        return;
      }

      if (!passwordRegex.test(password.value)) {
        error.value =
          'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.';
        isSubmitting.value = false;
        return;
      }

      try {
        await authStore.register(email.value, username.value, password.value);
        router.push('/about');
      } catch (err: any) {
    
        const msg = err?.response?.data?.message

        if (msg === 'Email already used.') {
            error.value = 'Cet email est déjà utilisé.'
        } else if (msg === 'Username already used.') {
            error.value = 'Ce nom d’utilisateur est déjà pris.'
        } else {
            error.value = 'Une erreur est survenue.'
        }

      } finally {
        isSubmitting.value = false;
      }
    };

    return {
      email,
      username,
      password,
      passwordConfirm,
      error,
      isSubmitting,
      handleRegister,
      authStore,
    };
  },
};
</script>
  