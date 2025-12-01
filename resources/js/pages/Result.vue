<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-lg w-full text-center">
            <div class="mb-6">
                <span class="text-6xl">🎉</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Quiz Finalizado!</h1>

            <div class="grid grid-cols-2 gap-4 mb-8 mt-6">
                <div class="bg-green-50 p-4 rounded-xl">
                    <p class="text-green-600 font-semibold uppercase text-xs">Acertos</p>
                    <p class="text-3xl font-bold text-green-700">{{ quizStore.score }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl">
                    <p class="text-blue-600 font-semibold uppercase text-xs">Tempo</p>
                    <p class="text-3xl font-bold text-blue-700">{{ quizStore.formattedTime }}</p>
                </div>
            </div>

            <router-link to="/" class="block w-full bg-gray-200 text-gray-800 font-bold py-3 px-4 rounded-lg hover:bg-gray-300 transition">
                Voltar ao Dashboard
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { useQuizStore } from '../stores/quiz';
const quizStore = useQuizStore();
</script>
