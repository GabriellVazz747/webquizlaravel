import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || '',
        errorMessage: ''
    }),

    getters: {
        isAuthenticated: (state) => !!state.token
    },

    actions: {
        initialize() {
            if (this.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            }
        },

        async login(credentials) {
            this.errorMessage = '';
            try {
                const response = await axios.post('/api/login', credentials);

                this.setSession(response.data.token, response.data.user);
                return true;
            } catch (error) {
                this.errorMessage = error.response?.data?.message || 'Erro ao entrar.';
                return false;
            }
        },

        async register(userData) {
            this.errorMessage = '';
            try {
                const response = await axios.post('/api/register', userData);

                this.setSession(response.data.token, response.data.user);
                return true;
            } catch (error) {
                const msg = error.response?.data?.message || 'Erro ao registrar.';
                this.errorMessage = msg;
                return false;
            }
        },

        async logout() {
            try {
                await axios.post('/api/logout');
            } catch (e) {
            } finally {
                this.clearSession();
            }
        },

        setSession(token, user) {
            this.token = token;
            this.user = user;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));

            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        },

        clearSession() {
            this.token = '';
            this.user = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            delete axios.defaults.headers.common['Authorization'];
        }
    }
});
