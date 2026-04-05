<template>
  <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Popular Destinations</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="dest in destinations" :key="dest.id" class="group relative rounded-lg overflow-hidden shadow-lg cursor-pointer hover:shadow-2xl transition" @click="$router.push(`/destinations/${dest.slug || dest.id}`)">
            <img :src="dest.image_url || 'https://via.placeholder.com/300'" class="w-full h-64 object-cover transform group-hover:scale-110 transition duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <h3 class="text-2xl font-bold">{{ dest.name }}</h3>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const destinations = ref([])

onMounted(async () => {
    try {
        const response = await api.get('/destinations')
        destinations.value = response.data
    } catch (e) {
        console.error(e)
    }
})
</script>
