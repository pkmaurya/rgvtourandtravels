<template>
  <div v-if="destination">
      <PageHero 
        :title="destination.name" 
        :subtitle="destination.description"
        :backgroundImage="destination.image_url || undefined"
      />
      
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">Tours in {{ destination.name }}</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
               <TourCard 
                  v-for="tour in tours" 
                  :key="tour.id"
                  :id="tour.id"
                  :slug="tour.slug"
                  :title="tour.title"
                  :price="tour.price"
                  :image="tour.images && tour.images[0] ? tour.images[0] : 'https://via.placeholder.com/400'"
                  :location="destination.name"
                  :featured="tour.featured"
                  :duration="tour.duration"
                  :description="tour.description"
               />
               <div v-if="tours.length === 0" class="col-span-3 text-center text-gray-500">
                   No tours available for this destination yet.
               </div>
          </div>
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import PageHero from '../components/PageHero.vue'
import TourCard from '../components/TourCard.vue'

const route = useRoute()
const destination = ref(null)
const tours = ref([])

onMounted(async () => {
    try {
        const destRes = await api.get(`/destinations/${route.params.id}`)
        destination.value = destRes.data
        
        // SEO: Update Title and Meta Tags
        if(destination.value) {
            document.title = `${destination.value.name} Tours | RGV Tour and Travels`
            // Meta Description
            let metaDesc = document.querySelector('meta[name="description"]')
            if (!metaDesc) {
                metaDesc = document.createElement('meta')
                metaDesc.name = "description"
                document.head.appendChild(metaDesc)
            }
            metaDesc.setAttribute("content", destination.value.description)
             // Canonical URL
             let canonical = document.querySelector('link[rel="canonical"]')
            if(!canonical) {
                 canonical = document.createElement('link')
                 canonical.rel = "canonical"
                 document.head.appendChild(canonical)
            }
            const slug = destination.value.slug || destination.value.id
            canonical.setAttribute("href", `${window.location.origin}/destinations/${slug}`)
        }

        if (destination.value && destination.value.id) {
             // Fetch tours specifically for this destination
             // Use a high limit to get all relevant tours, or implement pagination if needed
             const toursRes = await api.get(`/tours?destination_id=${destination.value.id}&limit=100`)
             
             if(toursRes.data.data) {
                 tours.value = toursRes.data.data
             } else if (Array.isArray(toursRes.data)) {
                 tours.value = toursRes.data
             } else {
                 tours.value = []
             }
        }
    } catch (e) {
        console.error("Failed to fetch destination details or tours", e)
    }
})
</script>
