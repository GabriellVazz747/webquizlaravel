<template>
  <div class="auth-container">
    <h1>Criar Conta</h1>
    <form @submit.prevent="handleRegister">
      <input type="text" v-model="name" placeholder="Nome" required />
      <input type="email" v-model="email" placeholder="Email" required />
      <input type="password" v-model="password" placeholder="Senha" required />
      <button type="submit" class="button-primary">Cadastrar</button>
    </form>
    <p class="mt-3">Já tem conta? <router-link to="/login">Faça Login</router-link></p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { apiFetch } from '../api'

const name = ref('')
const email = ref('')
const password = ref('')
const router = useRouter()

async function handleRegister() {
  try {
    await apiFetch('/api/register', {
      method: 'POST',
      body: JSON.stringify({ name: name.value, email: email.value, password: password.value })
    })
    alert('Cadastro realizado com sucesso! Faça login.')
    router.push('/login')
  } catch (error) {
    console.error('Erro no cadastro')
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