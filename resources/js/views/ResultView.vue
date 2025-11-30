<template>
  <div class="result-container">
    <h1>Resultado Final</h1>
    <div class="scoreboard">
      <p class="label">Pontuação Total:</p>
      <p class="value score-value">{{ store.score }} pontos</p>
      
      <p class="label">Tempo Total:</p>
      <p class="value">{{ store.totalTime }} segundos</p>
    </div>

    <button @click="backToDashboard" class="button-primary mt-4">Voltar ao Dashboard</button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useQuizStore } from '../store/quizStore'

const router = useRouter()
const store = useQuizStore()

function backToDashboard() {
  router.push('/dashboard')
}
</script>

<style scoped>
.result-container { 
  padding: 40px; 
  text-align: center; 
  max-width: 600px; 
  margin: auto; 
}
.scoreboard {
  background: #f9f9f9;
  padding: 30px;
  border-radius: 10px;
  border: 1px solid #eee;
  margin-top: 30px;
}
.label {
  font-size: 1.1em;
  color: #666;
  margin: 10px 0 5px;
}
.value {
  font-size: 1.8em;
  font-weight: bold;
  margin-bottom: 25px;
}
.score-value {
  color: #005eff;
}
.mt-4 { margin-top: 40px; }
</style>