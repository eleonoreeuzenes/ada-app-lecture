<template>
  <section class="bg-secondary-200 rounded-b-2xl py-6 px-6 md:px-96 relative">
    <!-- Header : Nom + Badge + Réglages -->
    <div class="flex justify-between items-start">
      <!-- Infos utilisateur -->
      <div>
        <h2 class="text-3xl font-bold">{{ user?.username }}</h2>
        <div class="flex items-center mt-1 text-sm bg-primary-700 text-primary-100 px-3 py-1 rounded-full w-fit">
          <i class="fas fa-certificate mr-1 text-primary-100"></i>
          {{ userLevel }}
        </div>
      </div>

      <!-- Icône settings -->
        <settings-component/>
    </div>

    <!-- Barre de progression -->
    <div class="mt-4 lg:w-1/3">
      <div class="w-full bg-white h-2 rounded-full overflow-hidden">
        <div
          class="bg-primary-500 h-2"
          :style="{ width: progressPercentage + '%' }"
        ></div>
      </div>
      <p class="text-sm mt-1 text-primary-800">
        <span class="font-bold">NIVEAU SUIVANT : </span>{{ pointsToNextLevel }} points
      </p>
    </div>

    <!-- Stats -->
    <div class="mt-4 flex justify-around items-center text-center lg:w-1/3">
      <div>
        <p class="text-2xl font-bold">{{ totalBooksRead }}</p>
        <p class="text-sm">livres lus</p>
      </div>

      <div class="border-l-2 border-primary-800 h-10"></div>

      <div>
        <p class="text-2xl font-bold">{{ user?.total_points }}</p>
        <p class="text-sm">points totaux</p>
      </div>
    </div>
  </section>
</template>
<script setup lang="ts">
import { computed } from 'vue'
import SettingsComponent from '@/components/SettingsComponent.vue';
import { useAuthStore } from '../stores/auth.ts'
import { useBookStore } from '@/stores/book.ts'
const authStore = useAuthStore()
const bookStore = useBookStore()

const user = authStore.user

const userLevel = authStore.userLevel

const pointsToNextLevel = computed(() => {
  const p = user?.total_points || 0
  if (p <= 50) return 51 - p
  if (p <= 150) return 151 - p
  if (p <= 300) return 301 - p
  return 0
})


const progressPercentage = computed(() => {
  const p = user?.total_points || 0
  const min = p <= 50 ? 0 : p <= 150 ? 51 : p <= 300 ? 151 : 301
  const max = p <= 50 ? 50 : p <= 150 ? 150 : p <= 300 ? 300 : p
  const progress = ((p - min) / (max - min)) * 100
  return Math.min(Math.round(progress), 100)
})

const totalBooksRead = bookStore.userbooks.filter(
  (book) => book.status === 'finished'
).length

</script>