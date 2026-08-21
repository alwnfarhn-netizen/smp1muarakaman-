import { defineStore } from 'pinia';
import api from '../plugins/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.user,
    },
    actions: {
        async login(email, password) {
            const backendUrl = import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000';
            await api.get(`${backendUrl}/sanctum/csrf-cookie`);
            const response = await api.post('/login', { email, password });
            this.user = response.data.user;
        },
        async logout() {
            await api.post('/logout');
            this.user = null;
        },
        async fetchUser() {
            try {
                const response = await api.get('/user');
                this.user = response.data;
            } catch (error) {
                this.user = null;
            }
        }
    }
});
