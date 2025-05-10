<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useBadgeStore } from '@/stores/badge';

const badgeStore = useBadgeStore();
const badges = ref<{ id: number; name: string; description: string; category: string }[]>([]);
const isLoading = ref(true); 

onMounted(async () => {
  isLoading.value = true; 
  await badgeStore.fetchBadges();
  badges.value = badgeStore.badges; 
  isLoading.value = false; 
});

const totalBadges = computed(() => badges.value.length);

const getBadgeImage = (badge: { name: string; category: string }) => {
  return new URL(`../assets/badge/${badge.category}.svg`, import.meta.url).href;
};
</script>

<template>
  <main class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">
      Tous mes Badges
      <span v-if="!isLoading">({{ totalBadges }})</span>
    </h1>

    <div v-if="isLoading" class="text-center text-gray-500">
      Chargement des badges...
    </div>

    <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
      <div
        v-for="badge in badges"
        :key="badge.id"
        class="flex flex-col items-center p-4"
      >
        <img
          :src="getBadgeImage(badge)"
          :alt="badge.name"
          class="w-20 h-20 object-cover rounded-full mb-2"
        />
        <p class="text-sm font-semibold text-gray-700">{{ badge.name }}</p>
      </div>
    </div>
  </main>
</template>