import { defineStore } from 'pinia'


export const useQuizStore = defineStore('quiz', {
state: () => ({
quizzes: [],
currentQuiz: null,
answers: {}
}),


actions: {
setQuizzes(data) {
this.quizzes = data
},


setCurrentQuiz(quiz) {
this.currentQuiz = quiz
},


saveAnswer(questionId, answer) {
this.answers[questionId] = answer
}
}
})