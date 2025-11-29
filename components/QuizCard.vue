<template>
<div class="card">
<h3>{{ quiz.title }}</h3>
<p>{{ quiz.description }}</p>
<button @click="openQuiz">Iniciar Quiz</button>
</div>
</template>
<script setup>
import { useRouter } from 'vue-router'
const props = defineProps(['quiz'])
const router = useRouter()


function openQuiz() {
router.push(`/quiz/${props.quiz.id}`)
}
</script>
<style scoped>
.card {
padding: 20px;
background: #f5f5f5;
border-radius: 10px;
margin-bottom: 20px;
}
</style>