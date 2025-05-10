<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useBadgeStore } from '@/stores/badge';

const badgeStore = useBadgeStore();
const badges = ref(badgeStore.badges);

onMounted(async () => {
  await badgeStore.fetchBadges();
});

const totalBadges = computed(() => badges.value.length);

const getBadgeImage = (badge: { name: string; category: string }) => {
  return new URL(`../assets/badge/${badge.category}.svg`, import.meta.url).href;
};
</script>


<template>
    <main class="container mx-auto px-4 py-8">
      <!-- Title with total number of badges -->
      <h1 class="text-2xl font-bold mb-6">Tous mes Badges ({{ totalBadges }})</h1>
  
      <!-- Badges grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <div
          v-for="badge in badges"
          :key="badge.id"
          class="flex flex-col items-center p-4"
        >
          <!-- Badge picture -->
          <img
            :src="getBadgeImage(badge)"
            :alt="badge.name"
            class="w-20 h-20 object-cover rounded-full mb-2"
          />
          <!-- Badge title -->
          <p class="text-sm font-semibold text-gray-700">{{ badge.name }}</p>
        </div>
      </div>
    </main>
  </template>