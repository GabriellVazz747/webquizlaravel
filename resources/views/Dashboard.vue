<template>
  <div class="dashboard-container">
    <header class="header">
      <h1>Dashboard do Quiz</h1>
      <button @click="handleLogout" class="logout-btn">Sair</button>
    </header>

    <div class="start-section">
      <h2>Pronto para testar seus conhecimentos?</h2>
      <p>O Quiz selecionará 10 questões aleatórias do banco.</p>
      <button @click="startQuiz" class="button-primary">Iniciar Novo Quiz</button>
    </div>

    <div class="ranking-section">
      <h2>🏆 Ranking dos Melhores Resultados</h2>
      <table v-if="ranking.length">
        <thead>
          <tr>
            <th>#</th>
            <th>Usuário</th>
            <th>Pontuação</th>
            <th>Tempo (s)</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in ranking" :key="index">
            <td>{{ index + 1 }}º</td>
            <td>{{ item.user }}</td>
            <td>{{ item.score }}</td>
            <td>{{ item.time }}</td>
          </tr>
        </tbody>
      </table>
      <p v-else>Carregando ranking...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useQuizStore } from '../store/quizStore'
import { apiFetch } from '../api'

const router = useRouter()
const store = useQuizStore()
const ranking = ref([])

onMounted(async () => {
  try {
    const data = await apiFetch('/api/ranking') 
    ranking.value = data.ranking || [
        { user: 'Maria (Mock)', score: 95, time: 120 },
        { user: 'João (Mock)', score: 88, time: 150 },
        { user: 'Ana (Mock)', score: 75, time: 180 },
    ]
  } catch (error) {
    console.error('Falha ao carregar ranking')
  }
})

async function startQuiz() {
  try {
    const quizData = await apiFetch('/api/quizzes/start', { method: 'POST' })
    store.setCurrentQuiz(quizData)
    store.startTimer()
    router.push(`/quiz/${quizData.id}`)
  } catch (error) {
    console.error('Falha ao iniciar quiz')
  }
}

function handleLogout() {
  store.logout()
  router.push('/login')
}
</script>

<style scoped>
.dashboard-container { padding: 20px; }
.header { display: flex; justify-content: space-between; align-items: center; }
.logout-btn { background: none; border: none; color: #cc0000; cursor: pointer; }
.start-section { 
  background: #f0f8ff; 
  padding: 25px; 
  border-radius: 10px; 
  margin-bottom: 30px; 
  text-align: center;
}
.ranking-section { margin-top: 30px; }
table { width: 100%; border-collapse: collapse; text-align: left; }
th, td { padding: 12px; border-bottom: 1px solid #eee; }
th { background-color: #f0f0f0; }
</style>