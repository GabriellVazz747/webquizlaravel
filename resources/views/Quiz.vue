<template>
<div class="container">
<h2>{{ quiz?.title }}</h2>


<div v-for="q in quiz.questions" :key="q.id" class="question">
<h3>{{ q.text }}</h3>


<button
v-for="alt in q.answers"
:key="alt.id"
@click="choose(q.id, alt.id)"
>
{{ alt.text }}
</button>
</div>


<button class="finish" @click="finalizar">Finalizar</button>
</div>
</template>
<script setup>
import { useQuizStore } from '../store/quizStore'
import { useRoute, useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'


const store = useQuizStore()
const route = useRoute()
const router = useRouter()
const quiz = ref(null)


onMounted(async () => {
const res = await fetch(`/api/quizzes/${route.params.id}`)
const data = await res.json()
quiz.value = data
store.setCurrentQuiz(data)
})


function choose(questionId, answerId) {
store.saveAnswer(questionId, answerId)
}


function finalizar() {
router.push('/resultado')
}
</script>
<style scoped>
.container { padding: 20px; }
.question { margin-bottom: 25px; }
.finish { background:#005eff; color:white; padding:10px; border:none; }
</style>