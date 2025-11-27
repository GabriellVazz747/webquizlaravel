<script setup>
import { ref, reactive, computed, onUnmounted } from 'vue';

const API_URL = 'http://127.0.0.1:8000/api';

const apiService = {
    _getHeaders(authenticated = false) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        if (authenticated) {
            const token = localStorage.getItem('auth_token');
            if (token) headers['Authorization'] = `Bearer ${token}`;
        }
        return headers;
    },

    async login(email, password) {
        const response = await fetch(`${API_URL}/login`, {
            method: 'POST',
            headers: this._getHeaders(),
            body: JSON.stringify({ email, password })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Falha na autenticação');
        }

        const data = await response.json();
        localStorage.setItem('auth_token', data.token);
        return data.user;
    },

    async register(name, email, password) {
        const response = await fetch(`${API_URL}/register`, {
            method: 'POST',
            headers: this._getHeaders(),
            body: JSON.stringify({
                name,
                email,
                password,
                password_confirmation: password
            })
        });

        if (!response.ok) {
             const errorData = await response.json();
             const errorMessage = errorData.errors
                ? Object.values(errorData.errors).flat().join(', ')
                : (errorData.message || 'Falha no registro');
             throw new Error(errorMessage);
        }

        const data = await response.json();
        localStorage.setItem('auth_token', data.token);
        return data.user;
    },

    async fetchQuestions() {
        const response = await fetch(`${API_URL}/questions`, {
            method: 'GET',
            headers: this._getHeaders(true)
        });

        // Tratamento de Erro Robusto:
        // Se a resposta não for OK (200-299), capturamos o status e o corpo do erro.
        if (!response.ok) {
            const status = response.status;
            let details = response.statusText;

            try {
                // Tenta ler o erro como JSON (padrão do Laravel)
                const errorJson = await response.json();
                details = errorJson.message || JSON.stringify(errorJson);
            } catch (e) {
                // Se falhar (ex: erro 500 retornando HTML), mantém o statusText
                console.error("Erro não-JSON da API:", e);
            }

            throw new Error(`Erro API (${status}): ${details}`);
        }

        const data = await response.json();
        const rawQuestions = data.data || data;

        return rawQuestions.map(q => ({
            id: q.id,
            text: q.question_text || q.description || q.text,
            options: [q.option_a, q.option_b, q.option_c, q.option_d],
            correct: _mapCorrectOptionIndex(q.correct_option)
        }));
    }
};

const _mapCorrectOptionIndex = (charOption) => {
    const mapping = { 'A': 0, 'B': 1, 'C': 2, 'D': 3 };
    return mapping[charOption] !== undefined ? mapping[charOption] : 0;
};

const currentView = ref('login');
const isLoading = ref(false);

const authState = reactive({
    isAuthenticated: false,
    user: null,
    token: localStorage.getItem('auth_token')
});

const loginForm = reactive({ email: 'admin@test.com', password: '123' });
const registerForm = reactive({ name: '', email: '', password: '' });

const quizData = ref([]);
const currentQuestionIndex = ref(0);
const selectedOption = ref(null);
const quizTimer = ref(0);
const timerInterval = ref(null);
const leaderboard = ref([
    { name: "Alan Turing", score: 100, time: 45 },
    { name: "Grace Hopper", score: 95, time: 50 },
]);

const quizResults = reactive({ correct: 0, incorrect: 0, score: 0, time: 0 });

const currentQuestion = computed(() => quizData.value[currentQuestionIndex.value] || {});
const isLastQuestion = computed(() => currentQuestionIndex.value === quizData.value.length - 1);

const handleLogin = async () => {
    isLoading.value = true;
    try {
        const user = await apiService.login(loginForm.email, loginForm.password);
        authState.user = user;
        authState.isAuthenticated = true;
        currentView.value = 'dashboard';
    } catch (e) {
        alert(e.message);
    } finally {
        isLoading.value = false;
    }
};

const handleRegister = async () => {
    isLoading.value = true;
    try {
        const user = await apiService.register(registerForm.name, registerForm.email, registerForm.password);
        authState.user = user;
        authState.isAuthenticated = true;
        currentView.value = 'dashboard';
    } catch (e) {
        alert(e.message);
    } finally {
        isLoading.value = false;
    }
};

const logout = () => {
    authState.user = null;
    authState.isAuthenticated = false;
    localStorage.removeItem('auth_token');
    currentView.value = 'login';
    stopTimer();
};

const goToDashboard = () => {
    if (authState.isAuthenticated) currentView.value = 'dashboard';
};

const startQuiz = async () => {
    isLoading.value = true;
    quizResults.correct = 0;
    quizResults.incorrect = 0;
    quizResults.score = 0;
    quizResults.time = 0;
    currentQuestionIndex.value = 0;
    selectedOption.value = null;
    quizTimer.value = 0;

    try {
        quizData.value = await apiService.fetchQuestions();
        if (!quizData.value || quizData.value.length === 0) {
            throw new Error("Nenhuma questão encontrada.");
        }
        isLoading.value = false;
        currentView.value = 'quiz';
        startTimer();
    } catch (e) {
        alert(e.message);
        isLoading.value = false;
    }
};

const selectOption = (index) => {
    selectedOption.value = index;
};

const confirmAnswer = () => {
    const question = currentQuestion.value;
    if (selectedOption.value === question.correct) {
        quizResults.correct++;
        quizResults.score += 10;
    } else {
        quizResults.incorrect++;
    }

    if (isLastQuestion.value) {
        finishQuiz();
    } else {
        currentQuestionIndex.value++;
        selectedOption.value = null;
    }
};

const startTimer = () => {
    stopTimer();
    timerInterval.value = setInterval(() => {
        quizTimer.value++;
    }, 1000);
};

const stopTimer = () => {
    if (timerInterval.value) {
        clearInterval(timerInterval.value);
        timerInterval.value = null;
    }
};

const finishQuiz = () => {
    stopTimer();
    quizResults.time = quizTimer.value;
    currentView.value = 'result';
};

const formatTime = (seconds) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
};

onUnmounted(() => {
    stopTimer();
});
</script>

<template>
    <div class="h-full flex flex-col bg-gray-50 text-gray-800">
        <header class="bg-white shadow-sm z-10 sticky top-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0 flex items-center cursor-pointer select-none" @click="goToDashboard">
                        <i class="fas fa-layer-group text-2xl text-quiz-primary mr-2"></i>
                        <h1 class="text-xl font-bold text-gray-800">Quiz<span class="text-quiz-primary">Grad</span></h1>
                    </div>

                    <div v-if="authState.isAuthenticated" class="flex items-center space-x-4">
                        <span class="text-gray-600 text-sm hidden sm:block">
                            Olá, <strong>{{ authState.user?.name || 'Usuário' }}</strong>
                        </span>
                        <button @click="logout" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50" title="Sair">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto relative p-4 scroll-smooth">
            <transition name="fade" mode="out-in">

                <div v-if="currentView === 'login'" class="min-h-full flex items-center justify-center" key="login">
                     <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                        <div class="text-center">
                            <h2 class="mt-2 text-3xl font-extrabold text-gray-900">Login</h2>
                            <p class="mt-2 text-sm text-gray-600">
                                Não tem conta? <a href="#" @click.prevent="currentView = 'register'" class="font-medium text-quiz-primary hover:text-indigo-500 transition-colors">Registre-se</a>
                            </p>
                        </div>
                        <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
                            <div class="rounded-md shadow-sm space-y-4">
                                <div>
                                    <input v-model="loginForm.email" type="email" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow" placeholder="Endereço de Email">
                                </div>
                                <div>
                                    <input v-model="loginForm.password" type="password" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow" placeholder="Senha">
                                </div>
                            </div>
                            <button type="submit" :disabled="isLoading" class="w-full py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white btn-primary uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                <span v-if="isLoading"><i class="fas fa-circle-notch fa-spin"></i> Processando...</span>
                                <span v-else>Entrar</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div v-else-if="currentView === 'register'" class="min-h-full flex items-center justify-center" key="register">
                     <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                        <div class="text-center">
                            <h2 class="mt-2 text-3xl font-extrabold text-gray-900">Criar Conta</h2>
                            <p class="mt-2 text-sm text-gray-600">
                                Já possui conta? <a href="#" @click.prevent="currentView = 'login'" class="font-medium text-quiz-primary hover:text-indigo-500 transition-colors">Fazer Login</a>
                            </p>
                        </div>
                        <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
                            <div class="rounded-md shadow-sm space-y-4">
                                <div>
                                    <input v-model="registerForm.name" type="text" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Nome Completo">
                                </div>
                                <div>
                                    <input v-model="registerForm.email" type="email" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Email">
                                </div>
                                <div>
                                    <input v-model="registerForm.password" type="password" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Senha">
                                </div>
                            </div>
                            <button type="submit" :disabled="isLoading" class="w-full py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white btn-primary uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                <span v-if="isLoading"><i class="fas fa-circle-notch fa-spin"></i> Criando conta...</span>
                                <span v-else>Registrar</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div v-else-if="currentView === 'dashboard'" class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-8" key="dashboard">
                    <div class="bg-quiz-gradient rounded-3xl shadow-xl overflow-hidden p-8 sm:p-12 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between text-white relative">
                        <div class="z-10 mb-6 sm:mb-0 max-w-lg">
                            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Desafie seu Conhecimento</h2>
                            <p class="text-indigo-100 text-lg">Responda a perguntas geradas dinamicamente e teste seus limites.</p>
                        </div>
                        <button @click="startQuiz" class="z-10 bg-white text-quiz-primary font-bold py-4 px-10 rounded-full shadow-lg hover:scale-105 transition transform duration-200 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-indigo-300">
                            <i class="fas fa-play mr-2"></i> Iniciar Quiz
                        </button>
                        <div class="absolute right-0 bottom-0 h-64 w-64 bg-white opacity-10 rounded-full transform translate-x-1/3 translate-y-1/3"></div>
                    </div>

                     <div class="bg-white shadow-lg overflow-hidden rounded-2xl border border-gray-100">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">
                                <i class="fas fa-trophy text-yellow-500 mr-2"></i> Melhores Resultados
                            </h3>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Top Rank</span>
                        </div>
                        <ul role="list" class="divide-y divide-gray-100">
                            <li v-for="(rank, index) in leaderboard" :key="index" class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-quiz-primary font-bold mr-4">
                                            {{ index + 1 }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ rank.name }}</p>
                                            <p class="text-xs text-gray-500">{{ formatTime(rank.time) }}</p>
                                        </div>
                                    </div>
                                    <div class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                        {{ rank.score }} pts
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-else-if="currentView === 'quiz'" class="max-w-4xl mx-auto flex flex-col h-full justify-center py-6" key="quiz">
                     <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Questão</span>
                            <div class="flex items-baseline">
                                <span class="text-3xl font-extrabold text-quiz-primary">{{ currentQuestionIndex + 1 }}</span>
                                <span class="text-gray-400 font-medium text-lg ml-1">/ {{ quizData.length }}</span>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-700 font-mono bg-gray-50 px-4 py-2 rounded-xl border border-gray-200">
                            <i class="fas fa-clock mr-3 text-quiz-primary animate-pulse"></i>
                            <span class="text-xl font-bold">{{ formatTime(quizTimer) }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-xl flex-1 flex flex-col border border-gray-100 relative overflow-hidden">
                        <div class="absolute top-0 left-0 h-1.5 bg-quiz-gradient transition-all duration-500" :style="{ width: ((currentQuestionIndex + 1) / quizData.length) * 100 + '%' }"></div>

                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-8 leading-relaxed">
                            {{ currentQuestion.text }}
                        </h3>

                        <div class="space-y-4 flex-1">
                            <div
                                v-for="(option, idx) in currentQuestion.options"
                                :key="idx"
                                @click="selectOption(idx)"
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': selectedOption === idx, 'border-gray-200 hover:border-indigo-300 hover:bg-gray-50': selectedOption !== idx}"
                                class="cursor-pointer border-2 rounded-xl p-5 flex items-center transition-all duration-200 group"
                            >
                                <div
                                    :class="selectedOption === idx ? 'bg-indigo-500 text-white shadow-md' : 'bg-gray-100 text-gray-500'"
                                    class="h-10 w-10 min-w-[2.5rem] rounded-full flex items-center justify-center font-bold mr-5 text-sm transition-colors duration-200"
                                >
                                    {{ ['A','B','C','D'][idx] }}
                                </div>
                                <span class="text-gray-700 font-medium text-lg group-hover:text-gray-900">{{ option }}</span>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-end">
                            <button
                                @click="confirmAnswer"
                                :disabled="selectedOption === null"
                                class="btn-primary text-white font-bold py-4 px-12 rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transform hover:-translate-y-1 transition-all duration-200 flex items-center text-lg"
                            >
                                {{ isLastQuestion ? 'Finalizar Quiz' : 'Próxima Questão' }}
                                <i class="fas ml-3" :class="isLastQuestion ? 'fa-flag-checkered' : 'fa-arrow-right'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else-if="currentView === 'result'" class="max-w-3xl mx-auto py-12 px-4 text-center h-full flex items-center justify-center" key="result">
                     <div class="bg-white rounded-3xl shadow-2xl p-12 relative overflow-hidden w-full">
                        <div class="absolute top-0 left-0 w-full h-3 bg-quiz-gradient"></div>

                        <div class="inline-flex items-center justify-center h-28 w-28 rounded-full bg-indigo-50 text-quiz-primary mb-8 shadow-inner">
                            <i class="fas fa-medal text-5xl"></i>
                        </div>

                        <h2 class="text-4xl font-extrabold text-gray-900 mb-2">Resultado Final</h2>
                        <p class="text-gray-500 mb-10">Você completou o desafio!</p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
                            <div class="bg-green-50 p-6 rounded-2xl border border-green-100">
                                <div class="text-green-600 font-bold text-sm uppercase tracking-wider mb-2">Acertos</div>
                                <div class="text-4xl font-black text-green-700">{{ quizResults.correct }}</div>
                            </div>
                            <div class="bg-red-50 p-6 rounded-2xl border border-red-100">
                                <div class="text-red-600 font-bold text-sm uppercase tracking-wider mb-2">Erros</div>
                                <div class="text-4xl font-black text-red-700">{{ quizResults.incorrect }}</div>
                            </div>
                            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                                <div class="text-blue-600 font-bold text-sm uppercase tracking-wider mb-2">Pontuação</div>
                                <div class="text-4xl font-black text-blue-700">{{ quizResults.score }}</div>
                            </div>
                        </div>

                        <div class="text-gray-400 font-mono mb-10 text-sm">
                            Tempo total: <span class="text-gray-800 font-bold">{{ formatTime(quizResults.time) }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <button @click="goToDashboard" class="px-8 py-4 border border-gray-200 rounded-xl text-gray-600 font-bold hover:bg-gray-50 hover:border-gray-300 transition-colors focus:outline-none">
                                Voltar ao Menu
                            </button>
                            <button @click="startQuiz" class="px-8 py-4 text-white btn-primary rounded-xl font-bold shadow-lg focus:outline-none">
                                Tentar Novamente
                            </button>
                        </div>
                    </div>
                </div>

            </transition>
        </main>
    </div>
</template>

<style scoped>
.bg-quiz-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.text-quiz-primary {
    color: #764ba2;
}

.btn-primary {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.btn-primary:hover:not(:disabled) {
    filter: brightness(110%);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(118, 75, 162, 0.3);
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease-in-out, transform 0.2s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateY(10px);
}
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
