<template>
    <div class="min-h-screen bg-gray-100">
        <Navbar />

        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Pronto para o desafio?</h2>
                    <p class="text-gray-600 mb-6">Teste seus conhecimentos agora mesmo. São perguntas aleatórias.</p>

                    <router-link
                        to="/quiz"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full text-lg shadow-lg transform transition hover:scale-105"
                    >
                        INICIAR QUIZ
                    </router-link>
                </div>
            </div>

            <div class="flex flex-col">
                <h3 class="text-xl font-bold text-gray-800 mb-4 ml-1">🏆 Ranking dos Melhores</h3>

                <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                    <div v-if="isLoading" class="p-8">
                        <LoadingSpinner text="Buscando os melhores jogadores..." />
                    </div>

                    <table v-else class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Posição
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usuário
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pontuação
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tempo
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(item, index) in rankingData" :key="index" :class="index < 3 ? 'bg-yellow-50' : ''">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="index === 0" class="text-2xl">🥇</span>
                                    <span v-else-if="index === 1" class="text-2xl">🥈</span>
                                    <span v-else-if="index === 2" class="text-2xl">🥉</span>
                                    <span v-else class="text-gray-500 font-bold ml-2">#{{ index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ item.score }} / 10
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ item.time }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const rankingData = ref([]);
const isLoading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/ranking');
        rankingData.value = response.data;
    } catch (error) {
        console.error("Erro ao carregar ranking", error);
    } finally {
        isLoading.value = false;
    }
});
</script>
