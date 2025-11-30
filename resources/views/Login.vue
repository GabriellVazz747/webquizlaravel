<template>
  <div class="auth-container">
    <h1>Login</h1>
    <form @submit.prevent="handleLogin">
      <input type="email" v-model="email" placeholder="Email" required />
      <input type="password" v-model="password" placeholder="Senha" required />
      <button type="submit" class="button-primary">Entrar</button>
    </form>
    <p class="mt-3">Ainda não tem conta? <router-link to="/register">Cadastre-se</router-link></p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuizStore } from '../store/quizStore'
import { apiFetch } from '../api'

const email = ref('teste@email.com')
const password = ref('password')
const router = useRouter()
const store = useQuizStore()

async function handleLogin() {
  try {
    const data = await apiFetch('/api/login', {
      method: 'POST',
      body: JSON.stringify({ email: email.value, password: password.value })
    })
    store.setToken(data.token) 
    router.push('/dashboard')
  } catch (error) {
    console.error('Erro de login')
  }
}
</script>

<style scoped>
.auth-container { padding: 40px; max-width: 400px; margin: auto; text-align: center; }
input { 
  display: block; 
  width: 100%; 
  padding: 12px; 
  margin-bottom: 15px; 
  border: 1px solid #ddd; 
  border-radius: 6px; 
}
.mt-3 { margin-top: 20px; }
</style>