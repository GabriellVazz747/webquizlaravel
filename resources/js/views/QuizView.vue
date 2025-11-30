<template>
  <div class="quiz-container">
    <div class="header-info">
      <h3>Questão {{ store.currentQuestionIndex + 1 }} de {{ store.totalQuestions }}</h3>
      <div class="timer">Tempo: {{ displayTime }}s</div>
    </div>

    <div v-if="currentQuestion" class="question-card">
      <p class="question-text">{{ currentQuestion.text }}</p>

      <div class="alternatives">
        <button 
          v-for="alt in currentQuestion.options" 
          :key="alt.id" 
          @click="selectAnswer(currentQuestion.id, alt.id)"
          :class="{ 'alt': true, 'selected': isSelected(alt.id) }"
        >
          {{ alt.text }}
        </button>
      </div>

      <button 
        @click="nextQuestion" 
        :disabled="!selectedAnswerId" 
        class="button-primary"
      >
        Confirmar e Próxima
      </button>
    </div>
    <div v-else>Carregando Quiz...</div>
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuizStore } from '../store/quizStore'
import { apiFetch } from '../api'

const store = useQuizStore()
const router = useRouter()
const intervalId = ref(null)

const currentQuestion = computed(() => 
  store.currentQuiz[store.currentQuestionIndex]
)
const selectedAnswerId = computed(() => 
  store.userAnswers[currentQuestion.value?.id]
)
const displayTime = computed(() => store.totalTime)

onMounted(() => {
  intervalId.value = setInterval(() => {
    store.totalTime++
  }, 1000)

  if (store.currentQuiz.length === 0) {
    router.push('/dashboard')
  }
})

onUnmounted(() => {
  clearInterval(intervalId.value)
})

function isSelected(answerId) {
  return selectedAnswerId.value === answerId
}

function selectAnswer(questionId, answerId) {
  store.saveAnswer(questionId, answerId)
}

async function nextQuestion() {
  if (store.currentQuestionIndex < store.totalQuestions - 1) {
    store.currentQuestionIndex++
  } else {
    store.stopTimer()
    await finalizeQuiz()
  }
}

async function finalizeQuiz() {
  try {
    const results = await apiFetch('/api/quizzes/finish', {
      method: 'POST',
      body: JSON.stringify({
        quiz_id: store.currentQuiz.id,
        answers: store.userAnswers,
        time: store.totalTime,
      })
    })

    store.calculateScore(results)
    router.push('/resultado')
  } catch (error) {
    console.error('Erro ao finalizar quiz')
    router.push('/resultado') 
  }
}
</script>

<style scoped>
.quiz-container { padding: 20px; }
.header-info { 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  margin-bottom: 25px;
}
.timer { 
  font-weight: bold; 
  color: white; 
  background: #ff5722; 
  padding: 5px 10px; 
  border-radius: 5px;
}
.question-card {
  padding: 30px;
  border: 1px solid #eee;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}
.question-text { 
  font-size: 1.2em; 
  font-weight: 500; 
  margin-bottom: 30px; 
}
.alt {
  display: block;
  background: #f8f8f8;
  padding: 15px;
  width: 100%;
  margin-bottom: 10px;
  border-radius: 6px;
  border: 1px solid #ddd;
  text-align: left;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}
.alt:hover {
  background: #f0f0f0;
}
.selected {
  background: #e6f0ff;
  border-color: #005eff;
  font-weight: bold;
}
button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>