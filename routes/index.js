import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Quiz from '../views/Quiz.vue'
import Result from '../views/Result.vue'
import Dashboard from '../views/Dashboard.vue'
const routes = [
{ path: '/', component: Home },
{ path: '/quiz/:id', component: Quiz },
{ path: '/resultado', component: Result },
{ path: '/dashboard', component: Dashboard }
]
export default createRouter({
history: createWebHistory(),
routes
})