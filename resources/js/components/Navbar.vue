<template>
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <router-link to="/" class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-blue-600">Web Quiz</span>
                    </router-link>
                </div>

                <div class="flex items-center">
                    <div class="ml-3 relative flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">
                            Olá, {{ authStore.user?.name || 'Visitante' }}
                        </span>

                        <button
                            @click="handleLogout"
                            class="text-sm text-red-600 hover:text-red-800 font-semibold border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition"
                        >
                            Sair
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = () => {
    authStore.logout();
    router.push('/login');
};
</script>
