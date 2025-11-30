import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useQuizStore = defineStore('quiz', () => {
  const token = ref(localStorage.getItem('token') || null)
  const currentQuiz = ref([])
  const userAnswers = ref({})
  const startTime = ref(null)
  const totalTime = ref(0)
  const score = ref(0)
  const totalQuestions = ref(0)
  
  const isAuthenticated = computed(() => !!token.value)
  const currentQuestionIndex = ref(0)
  
  function setToken(newToken) {
    token.value = newToken
    localStorage.setItem('token', newToken)
  }

  function logout() {
    token.value = null
    localStorage.removeItem('token')
  }

  function startTimer() {
    startTime.value = Date.now()
    totalTime.value = 0
  }

  function stopTimer() {
    totalTime.value = Math.floor((Date.now() - startTime.value) / 1000)
  }

  function setCurrentQuiz(quizData) {
    currentQuiz.value = quizData.questions
    totalQuestions.value = quizData.questions.length
    userAnswers.value = {}
    currentQuestionIndex.value = 0
  }

  function saveAnswer(questionId, answerId) {
    userAnswers.value[questionId] = answerId
  }

  function calculateScore(results) {
    score.value = results.score
    totalTime.value = results.time
  }

  return { 
    token, 
    isAuthenticated, 
    currentQuiz, 
    userAnswers, 
    totalTime, 
    score, 
    totalQuestions,
    currentQuestionIndex,
    setToken, 
    logout, 
    setCurrentQuiz, 
    saveAnswer, 
    startTimer, 
    stopTimer, 
    calculateScore 
  }
})