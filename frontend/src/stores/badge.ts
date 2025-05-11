import { defineStore } from 'pinia';
import api from '@/services/api';

export const useBadgeStore = defineStore('badge', {
  state: () => ({
    badges: [] as Array<{
      id: number;
      name: string;
      description: string;
      category: string;
    }>,
    isLoading: false,
    error: null as string | null,
  }),

  actions: {
    async fetchBadges() {
      this.isLoading = true;
      this.error = null;

      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          throw new Error('No authentication token found.');
        }
        const response = await api.get('/me/badges');
        this.badges = response.data;
      } catch (error: any) {
        console.error('Error fetching badges:', error);
        this.error = error.response?.data?.message || 'Erreur lors de la récupération des badges.';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },
    }
});
