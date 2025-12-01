<template>
    <div class="min-h-screen bg-gray-100 flex flex-col items-center py-10 px-4">

        <div v-if="quizStore.loading" class="mt-20 flex flex-col items-center">
            <LoadingSpinner text="Preparando as perguntas..." />
        </div>

        <div v-else-if="quizStore.questions.length > 0" class="w-full flex flex-col items-center">

            <div class="w-full max-w-2xl flex justify-between items-center mb-6">
                <div class="text-gray-600 font-medium">
                    Pergunta <span class="text-blue-600 font-bold text-xl">{{ quizStore.currentQuestionIndex + 1 }}</span>
                    <span class="text-sm">/ {{ quizStore.totalQuestions }}</span>
                </div>

                <div class="bg-white px-4 py-2 rounded-full shadow-sm text-gray-700 font-mono">
                    ⏱ {{ timerDisplay }}
                </div>
            </div>

            <div class="w-full max-w-2xl mb-8">
                <QuestionCard
                    v-if="quizStore.currentQuestion"
                    :question="quizStore.currentQuestion"
                    :selectedOption="selectedOption"
                    @select="handleSelectOption"
                />
            </div>

            <div class="w-full max-w-2xl text-right">
                <button
                    @click="confirmAnswer"
                    :disabled="selectedOption === null"
                    class="bg-blue-600 text-white font-bold py-3 px-10 rounded-lg shadow-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700 transform hover:-translate-y-1"
                >
                    {{ quizStore.isLastQuestion ? 'Finalizar' : 'Próxima' }}
                </button>
            </div>
        </div>

        <div v-else class="mt-20 text-gray-500">
            Não foi possível carregar as perguntas. Tente recarregar a página.
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useQuizStore } from '../stores/quiz';
import { useRouter } from 'vue-router';
import QuestionCard from '../components/QuestionCard.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const quizStore = useQuizStore();
const router = useRouter();
const selectedOption = ref(null);
const timerDisplay = ref('00:00');
let timerInterval = null;

const updateTimer = () => {
    if (!quizStore.startTime) return;
    const diff = Math.floor((Date.now() - quizStore.startTime) / 1000);
    const minutes = Math.floor(diff / 60).toString().padStart(2, '0');
    const seconds = (diff % 60).toString().padStart(2, '0');
    timerDisplay.value = `${minutes}:${seconds}`;
};

onMounted(async () => {
    await quizStore.startQuiz();

    timerInterval = setInterval(updateTimer, 1000);
});

onUnmounted(() => {
    clearInterval(timerInterval);
});

const handleSelectOption = (index) => {
    selectedOption.value = index;
};

const confirmAnswer = async () => {
    if (selectedOption.value === null) return;

    quizStore.submitAnswer(selectedOption.value);

    if (quizStore.isLastQuestion) {
        await quizStore.finishQuiz();
        router.push('/result');
    } else {
        quizStore.nextQuestion();
        selectedOption.value = null;
    }
};
</script>
