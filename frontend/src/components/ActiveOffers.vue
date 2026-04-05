<template>
  <section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Special Offers</h2>
        <p class="mt-4 text-lg text-gray-500">Grab these limited-time deals before they expire!</p>
      </div>

      <div v-if="loading" class="text-center py-10">
         <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
      </div>

      <div v-else-if="offers.length === 0" class="text-center text-gray-500">
        No active offers at the moment. Check back later!
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="offer in offers" :key="offer.id" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100 relative group">
          <!-- Decorative Top Pattern -->
          <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
          
          <div class="p-6">
             <div class="flex justify-between items-start">
                <div>
                   <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-2">
                      {{ offer.discount_type === 'percentage' ? 'Discount' : 'Flat Off' }}
                   </span>
                   <h3 class="text-xl font-bold text-gray-900 mb-2">{{ offer.title }}</h3>
                </div>
                <div class="text-right">
                   <div class="text-2xl font-extrabold text-indigo-600">
                      {{ getDiscountText(offer) }}
                   </div>
                   <div class="text-xs text-gray-500 uppercase tracking-wide">OFF</div>
                </div>
             </div>
             
             <p class="text-gray-600 mb-4 text-sm line-clamp-2">{{ offer.description }}</p>
             
             <div class="bg-gray-50 rounded-lg p-3 mb-4 border border-gray-100 border-dashed">
                <div v-if="offer.coupon_code" class="flex justify-between items-center">
                   <span class="text-xs text-gray-500">Use Coupon Code:</span>
                   <span class="font-mono font-bold text-gray-900 bg-white px-2 py-1 rounded border border-gray-200 select-all">{{ offer.coupon_code }}</span>
                </div>
                <div v-else class="text-sm text-gray-500 text-center italic">
                   Discount applied automatically
                </div>
             </div>

             <div class="flex items-center justify-between text-xs text-gray-400 mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center">
                   <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                   <span>Expires: {{ formatDate(offer.end_date) }}</span>
                </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const offers = ref([])
const loading = ref(true)

const fetchOffers = async () => {
  try {
    const response = await api.get('/offers/active')
    offers.value = response.data
  } catch (error) {
    console.error("Failed to fetch active offers", error)
  } finally {
    loading.value = false
  }
}

const getDiscountText = (offer) => {
    const val = parseInt(offer.discount_value)
    if (isNaN(val)) return offer.discount_value // Return original string if not a number
    
    if (offer.discount_type === 'percentage') {
        return `${val}%`
    }
    return `₹${val}`
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    try {
        const date = new Date(dateString)
        if (isNaN(date.getTime())) return dateString // Return original if invalid date
        return date.toLocaleDateString(undefined, {
            day: 'numeric', month: 'short', year: 'numeric'
        })
    } catch (e) {
        return dateString
    }
}

onMounted(() => {
  fetchOffers()
})
</script>
