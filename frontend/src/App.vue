<template>
    <div class="min-h-screen flex flex-col">
    <Topbar v-if="!$route.meta.hideLayout" />
    <nav v-if="!$route.meta.hideLayout" class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <router-link to="/" class="text-2xl font-bold text-brand-secondary font-serif">RGV Tour and Travels</router-link>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <router-link to="/" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</router-link>
              <router-link to="/tours" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Tours</router-link>
              <router-link to="/destinations" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Destinations</router-link>
              <router-link to="/contact" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Contact Us</router-link>
              <router-link v-if="authStore.isAdmin" to="/admin" class="border-transparent text-indigo-600 hover:border-indigo-700 hover:text-indigo-800 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold">Admin Panel</router-link>
            </div>
          </div>
          <div class="flex items-center">
            <div v-if="authStore.isAuthenticated" class="relative z-50" ref="dropdownRef">
               <button @click="toggleDropdown" class="flex items-center space-x-2 focus:outline-none hover:bg-gray-50 rounded-full p-1 transition-colors border border-transparent hover:border-gray-200">
                  <!-- Profile Image or Initials -->
                  <img v-if="authStore.user?.profile_image" :src="authStore.user.profile_image" class="w-8 h-8 rounded-full object-cover border border-gray-200" alt="User">
                  <div v-else class="w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center text-white font-bold text-xs shadow-sm">
                      {{ getUserInitials() }}
                  </div>
                  <span class="font-medium text-gray-700 hidden md:block">{{ authStore.user?.name || (authStore.user?.first_name ? authStore.user.first_name : 'User') }}</span>
                   <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': isDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
               </button>

               <!-- Dropdown Menu -->
               <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg py-2 ring-1 ring-black ring-opacity-5 z-50 transform origin-top-right transition-all">
                  <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                      <p class="text-xs text-brand-secondary font-semibold uppercase tracking-wider">Signed in as</p>
                      <p class="text-sm font-medium text-gray-900 truncate mt-1">{{ authStore.user?.email }}</p>
                  </div>
                  
                  <div class="py-1">
                    <router-link to="/profile" @click="isDropdownOpen = false" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-secondary transition-colors">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 group-hover:bg-white group-hover:shadow-sm transition-all text-gray-500 group-hover:text-brand-secondary">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        Your Profile
                    </router-link>

                    <router-link v-if="authStore.isAdmin" to="/admin" @click="isDropdownOpen = false" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-secondary transition-colors">
                         <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 group-hover:bg-white group-hover:shadow-sm transition-all text-gray-500 group-hover:text-brand-secondary">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </span>
                        Admin Dashboard
                    </router-link>
                  </div>
                  
                  <div class="border-t border-gray-100 py-1">
                    <button @click="logout" class="w-full text-left group flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <span class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center mr-3 group-hover:bg-white group-hover:shadow-sm transition-all text-red-500">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </span>
                        Sign out
                    </button>
                  </div>
               </div>
            </div>
            <div v-else>
              <router-link to="/login" class="text-gray-500 hover:text-gray-700 px-3">Login</router-link>
              <router-link to="/register" class="bg-brand-secondary text-white px-4 py-2 rounded hover:bg-brand-accent transition">Register</router-link>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <main class="flex-grow bg-gray-50">
      <router-view></router-view>
    </main>

    <ChatbotWidget v-if="!$route.meta.hideLayout" />

    <Footer v-if="!$route.meta.hideLayout" />
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/auth'
import { useRouter } from 'vue-router'
import { ref, onMounted, onUnmounted } from 'vue'
import Footer from './components/Footer.vue'
import Topbar from './components/Topbar.vue'
import ChatbotWidget from './components/ChatbotWidget.vue'

const authStore = useAuthStore()
const router = useRouter()

const isDropdownOpen = ref(false)
const dropdownRef = ref(null)

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value
}

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        isDropdownOpen.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', closeDropdown)
})

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown)
})

const getUserInitials = () => {
    if (!authStore.user) return 'U'
    const name = authStore.user.name || authStore.user.first_name || 'User'
    return name.charAt(0).toUpperCase()
}

const logout = () => {
  authStore.logout()
  isDropdownOpen.value = false
  router.push('/login')
}
</script>
