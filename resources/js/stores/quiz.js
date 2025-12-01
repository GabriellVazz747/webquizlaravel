import { defineStore } from 'pinia';
import axios from 'axios';

export const useQuizStore = defineStore('quiz', {
    state: () => ({
        questions: [],
        currentQuestionIndex: 0,
        score: 0,
        startTime: null,
        endTime: null,
        loading: false,
    }),

    getters: {
        currentQuestion: (state) => state.questions[state.currentQuestionIndex],
        totalQuestions: (state) => state.questions.length,
        isLastQuestion: (state) => state.questions.length > 0 && state.currentQuestionIndex === state.questions.length - 1,
        formattedTime: (state) => {
            if (!state.startTime || !state.endTime) return '00:00';
            const diff = Math.floor((state.endTime - state.startTime) / 1000);
            const minutes = Math.floor(diff / 60).toString().padStart(2, '0');
            const seconds = (diff % 60).toString().padStart(2, '0');
            return `${minutes}:${seconds}`;
        }
    },

    actions: {
        async startQuiz() {
            this.loading = true;
            this.currentQuestionIndex = 0;
            this.score = 0;
            this.questions = [];

            try {
                const response = await axios.get('/api/questions');
                this.questions = response.data;
                this.startTime = Date.now();
                this.endTime = null;
            } catch (error) {
                console.error("Erro ao carregar quiz:", error);
            } finally {
                this.loading = false;
            }
        },

        submitAnswer(optionIndex) {
            const question = this.currentQuestion;
            const selectedOption = question.options[optionIndex];

            if (selectedOption.is_correct == true) {
                this.score++;
            }
        },

        nextQuestion() {
            if (!this.isLastQuestion) {
                this.currentQuestionIndex++;
            } else {
                this.finishQuiz();
            }
        },

        async finishQuiz() {
            this.endTime = Date.now();

            if (!this.startTime) this.startTime = this.endTime;

            const timeInSeconds = Math.floor((this.endTime - this.startTime) / 1000);

            try {
                await axios.post('/api/quiz/result', {
                    score: this.score,
                    time_seconds: timeInSeconds
                });
            } catch (error) {
                console.error("Erro ao salvar resultado:", error);
            }
        }
    }
});
