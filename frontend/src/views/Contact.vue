<template>
  <div class="min-h-screen bg-gray-50 pb-12">
    <PageHero 
      title="Contact Us" 
      subtitle="We'd love to hear from you. Send us a message or visit our office."
      backgroundImage="https://images.unsplash.com/photo-1542435503-956c469947f6?auto=format&fit=crop&q=80&w=1974"
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
          <form class="space-y-6" @submit.prevent="submitForm">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700"> Name </label>
              <div class="mt-1">
                <input v-model="form.name" id="name" name="name" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              </div>
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700"> Email address </label>
              <div class="mt-1">
                <input v-model="form.email" id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              </div>
            </div>

            <div>
              <label for="subject" class="block text-sm font-medium text-gray-700"> Subject </label>
              <div class="mt-1">
                <input v-model="form.subject" id="subject" name="subject" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              </div>
            </div>

            <div>
              <label for="message" class="block text-sm font-medium text-gray-700"> Message </label>
              <div class="mt-1">
                <textarea v-model="form.message" id="message" name="message" rows="4" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
              </div>
            </div>

            <div>
              <label for="captcha" class="block text-sm font-medium text-gray-700"> Security Check: {{ captcha.num1 }} + {{ captcha.num2 }} = ? </label>
              <div class="mt-1">
                <input v-model="captcha.answer" id="captcha" name="captcha" type="number" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter the sum">
              </div>
            </div>

            <div v-if="successMessage" class="rounded-md bg-green-50 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-green-800">
                    {{ successMessage }}
                  </p>
                </div>
              </div>
            </div>

            <div v-if="errorMessage" class="rounded-md bg-red-50 p-4">
               <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-red-800">
                    {{ errorMessage }}
                  </p>
                </div>
              </div>
            </div>

            <div>
              <button type="submit" :disabled="loading" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ loading ? 'Sending...' : 'Send Message' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 flex flex-col justify-center space-y-8">
            <div class="flex items-start">
                 <div class="flex-shrink-0">
                     <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                         <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                         </svg>
                     </div>
                 </div>
                 <div class="ml-4">
                     <h3 class="text-lg font-medium text-gray-900">Phone</h3>
                     <p class="mt-2 text-base text-gray-500">+91 123 456 7890</p>
                     <p class="text-base text-gray-500">+91 098 765 4321</p>
                 </div>
            </div>

            <div class="flex items-start">
                 <div class="flex-shrink-0">
                     <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                         <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                         </svg>
                     </div>
                 </div>
                 <div class="ml-4">
                     <h3 class="text-lg font-medium text-gray-900">Email</h3>
                     <p class="mt-2 text-base text-gray-500">info@incredibleindiatours.com</p>
                     <p class="text-base text-gray-500">support@incredibleindiatours.com</p>
                 </div>
            </div>

             <div class="flex items-start">
                 <div class="flex-shrink-0">
                     <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                         </svg>
                     </div>
                 </div>
                 <div class="ml-4">
                     <h3 class="text-lg font-medium text-gray-900">Office</h3>
                     <p class="mt-2 text-base text-gray-500">123 Tourism Lane, Cannaught Place,<br>New Delhi, India 110001</p>
                 </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import api from '../services/api'
import PageHero from '../components/PageHero.vue'

const form = reactive({
  name: '',
  email: '',
  subject: '',
  message: '',
  hasError: false
})

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// Math Captcha
const captcha = reactive({
    num1: 0,
    num2: 0,
    answer: '',
    expected: 0
})

const generateCaptcha = () => {
    captcha.num1 = Math.floor(Math.random() * 10) + 1
    captcha.num2 = Math.floor(Math.random() * 10) + 1
    captcha.expected = captcha.num1 + captcha.num2
    captcha.answer = ''
}

import { onMounted } from 'vue'
onMounted(() => {
    generateCaptcha()
})

const submitForm = async () => {
    // Validate Captcha
    if (parseInt(captcha.answer) !== captcha.expected) {
        errorMessage.value = `Incorrect math answer. Please equal ${captcha.num1} + ${captcha.num2}`
        return
    }

    loading.value = true
    successMessage.value = ''
    errorMessage.value = ''

    try {
        await api.post('/contact', form)
        successMessage.value = 'Thank you! Your message has been sent successfully.'
        form.name = ''
        form.email = ''
        form.subject = ''
        form.message = ''
        generateCaptcha() // Reset captcha
    } catch (error) {
        console.error("Submit failed", error)
        errorMessage.value = error.response?.data?.message || 'Something went wrong. Please try again.'
        generateCaptcha() // Reset captcha on error too
    } finally {
        loading.value = false
    }
}
</script>
