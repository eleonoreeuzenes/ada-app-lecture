import { defineStore } from 'pinia';
import api from '@/services/api'; // Ensure you have an Axios instance configured

export const useBookStore = defineStore('book', {
  state: () => ({
    books: [] as Array<{
      id: number;
      title: string;
      author: string;
      total_pages: number;
      genre_id: number;
      genre: { id: number; name: string };
    }>,
    userbooks: [] as Array<{
      id: number,
      title: string,
      author: string,
      status: string,
      pages_read: number,
      total_pages: number,
      genre: string,
    }>, 
    genres: [] as Array<{ id: number; name: string }>,
    isLoading: false,
    error: null as string | null, 
  }),

  actions: {
    async fetchGenres() {
      this.isLoading = true;
      this.error = null;
      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          throw new Error('No authentication token found.');
        }
        const response = await api.get('/genres');
        this.genres = response.data;
      } catch (error: any) {
        console.error('Error fetching genres:', error);
        this.error = error.response?.data?.message || 'Erreur lors de la récupération des genres.';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async createBook(bookData: {
      title: string;
      author: string;
      total_pages: number;
      genre_id: number;
      status: string;
      pages_read: number;
    }) {
      this.error = null;

      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          throw new Error('No authentication token found.');
        }

        await api.post('/user-books/full', bookData);
        this.fetchUserBooks();
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Erreur lors de la création du livre.';
        throw error;
      }
    },

    async searchBooks(title: string) {
      this.isLoading = true;
      this.error = null;

      try {
        const token = localStorage.getItem('authToken'); 
        if (!token) {
          throw new Error('No authentication token found.');
        }

        const response = await api.get('/books/search', {
          params: { title }, 
        });
        
        this.books = response.data.map((book: any) => ({
          id: book.id,
          title: book.title,
          author: book.author,
          total_pages: book.total_pages,
          genre_id: book.genre_id,
          genre: {
            id: book.genre.id,
            name: book.genre.name,
          },
        }));
      } catch (error: any) {
        this.error = error.response?.data?.message || 'An error occurred while searching for books.';
      } finally {
        this.isLoading = false;
      }
    },
    async fetchUserBooks() {
      this.isLoading = true;
      this.error = null;

      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          throw new Error('No authentication token found.');
        }

        const response = await api.get('/user-books');

        this.userbooks = response.data.map((userBook: any) => ({
          id: userBook.id,
          title: userBook.book.title,
          author: userBook.book.author,
          status: userBook.status,
          pages_read: userBook.pages_read,
          total_pages: userBook.book.total_pages,
          genre: userBook.book.genre.name,
        }));
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Erreur lors de la récupération des livres.';
      } finally {
        this.isLoading = false;
      }
    },
    async updateUserBook(updatedBook: {
      id: number;
      status: string;
      pages_read: number;
    }) {
      this.isLoading = true;
      this.error = null;
    
      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          throw new Error('No authentication token found.');
        }
    
        const response = await api.patch(`/user-books/${updatedBook.id}`, {
          status: updatedBook.status,
          pages_read: updatedBook.pages_read,
        });
    
        const bookIndex = this.userbooks.findIndex((book) => book.id === updatedBook.id);
        if (bookIndex !== -1) {
          this.userbooks[bookIndex].status = updatedBook.status;
          this.userbooks[bookIndex].pages_read = updatedBook.pages_read;
        }
        this.fetchUserBooks();
        return response.data
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Erreur lors de la mise à jour du livre.';
      } finally {
        this.isLoading = false;
      }
    },
    async addBookToLibrary(payload: { book_id: number; status: string; pages_read: number }) {
      this.isLoading = true;
      this.error = null;
    
      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          throw new Error('No authentication token found.');
        }
    
        await api.post('/user-books', payload);
        await this.fetchUserBooks();
        
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Erreur lors de l\'ajout du livre.';
      } finally {
        this.isLoading = false;
      }
    }
  },
  getters: {
    getUserBookById: (state) => {
      return (id: number) => state.userbooks.find(book => book.id === id)
    }
  }
  
});