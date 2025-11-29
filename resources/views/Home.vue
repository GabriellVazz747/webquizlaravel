<template>
<div class="container">
<h1>Quizzes Disponíveis</h1>


<QuizCard v-for="q in quizzes" :key="q.id" :quiz="q" />
</div>
</template>
<script setup>
import QuizCard from '../components/QuizCard.vue'
import { useQuizStore } from '../store/quizStore'
import { onMounted } from 'vue'


const store = useQuizStore()
onMounted(async () => {
const res = await fetch('/api/quizzes')
const data = await res.json()
store.setQuizzes(data)
})


const quizzes = store.quizzes
</script>
<style scoped>
.container {
padding: 20px;
}
</style>