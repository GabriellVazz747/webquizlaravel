<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Criar Conta</h1>
                <p class="text-gray-500">Junte-se ao Web Quiz</p>
            </div>

            <form @submit.prevent="handleRegister">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome</label>
                    <input v-model="form.name" id="name" type="text" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Seu Nome" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">E-mail</label>
                    <input v-model="form.email" id="email" type="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="seu@email.com" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Senha</label>
                    <input v-model="form.password" id="password" type="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="********" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">Confirmar Senha</label>
                    <input v-model="form.password_confirmation" id="password_confirmation" type="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="********" required>
                </div>

                <div v-if="authStore.errorMessage" class="mb-4 text-red-500 text-sm text-center">
                    {{ authStore.errorMessage }}
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                    Registrar
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    Já tem uma conta?
                    <router-link to="/login" class="text-blue-600 hover:underline">Faça Login</router-link>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const handleRegister = async () => {
    const success = await authStore.register(form);
    if (success) {
        router.push('/');
    }
};
</script>
