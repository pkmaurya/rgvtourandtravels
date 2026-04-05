<template>
  <div class="flex h-screen bg-white">
    <!-- Left Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
      <div class="w-full max-w-md space-y-8">
        <div>
          <h2 class="mt-6 text-3xl font-bold tracking-tight text-gray-900 font-serif">Welcome Back</h2>
          <p class="mt-2 text-sm text-gray-600">
            Or
            <router-link to="/register" class="font-medium text-india-orange hover:text-orange-500">create a new account</router-link>
          </p>
        </div>
        
        <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
          <input type="hidden" name="remember" value="true" />
          <div class="space-y-4">
            <div>
              <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
              <input id="email-address" name="email" type="email" autocomplete="email" required v-model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-india-orange focus:ring-india-orange sm:text-sm p-2 border" />
            </div>
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <input id="password" name="password" type="password" autocomplete="current-password" required v-model="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-india-orange focus:ring-india-orange sm:text-sm p-2 border" />
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="text-sm">
              <router-link to="/forgot-password" class="font-medium text-india-orange hover:text-orange-500">Forgot your password?</router-link>
            </div>
          </div>

          <div>
            <button type="submit" :disabled="isLoading" class="group relative flex w-full justify-center rounded-md bg-india-orange px-3 py-2 text-sm font-semibold text-white hover:bg-orange-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-india-orange disabled:opacity-70 disabled:cursor-not-allowed">
              <span v-if="isLoading" class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              {{ isLoading ? 'Signing in...' : 'Sign in' }}
            </button>
          </div>
          
          <div class="text-sm text-center">
            <router-link to="/" class="font-medium text-india-orange hover:text-orange-500">
              <span aria-hidden="true">&larr;</span> Back to Home
            </router-link>
          </div>

          <div v-if="error" class="text-red-500 text-center text-sm p-2 bg-red-50 rounded">
              {{ error }}
          </div>
        </form>
      </div>
    </div>

    <!-- Right Side - Image -->
    <div class="hidden lg:flex w-1/2 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1524492412937-b28074a5d7da?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1771&q=80')">
      <div class="flex items-center h-full w-full bg-gray-900 bg-opacity-40">
        <div class="text-white px-20">
          <h1 class="text-5xl font-bold mb-6 font-serif">Welcome Back</h1>
          <p class="text-xl">Sign in to continue your journey with Incredible India Tours.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const isLoading = ref(false)
const authStore = useAuthStore()
const router = useRouter()

const handleLogin = async () => {
    error.value = ''
    isLoading.value = true
    try {
        await authStore.login(email.value, password.value)
        router.push('/')
    } catch (err) {
        if (err.response && err.response.data && err.response.data.message) {
            error.value = err.response.data.message
        } else {
            error.value = 'Invalid email or password'
        }
    } finally {
        isLoading.value = false
    }
}
</script>
