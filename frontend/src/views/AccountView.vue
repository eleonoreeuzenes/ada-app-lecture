<script setup lang="ts">
import SettingsComponent from '@/components/settingsComponent.vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
console.log('user', authStore.user);

</script>

<template>
  <main>
    <settings-component />
    <p class="text-lg font-semibold">
      Niveau : {{ authStore.userLevel }} ({{ authStore.user?.total_points }} pts)
    </p>

  </main>
</template>