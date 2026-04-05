<template>
    <div v-if="offers.length > 0" class="relative bg-indigo-600">
        <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
            <div class="pr-16 sm:text-center sm:px-16">
                <p class="font-medium text-white">
                    <span class="md:hidden">
                        {{ currentOffer.title }} - {{ getDiscountText(currentOffer) }} Off!
                    </span>
                    <span class="hidden md:inline">
                        <span class="font-bold">{{ currentOffer.title }}</span>
                        <span class="mx-2">&bull;</span>
                        {{ currentOffer.description }}
                        <span class="ml-2 font-bold px-2 py-0.5 rounded bg-white text-indigo-600">
                            {{ getDiscountText(currentOffer) }} OFF
                        </span>
                        <span v-if="currentOffer.coupon_code" class="ml-2">
                             Use Code: <span class="font-mono font-bold">{{ currentOffer.coupon_code }}</span>
                        </span>
                    </span>
                </p>
            </div>
            <!-- Slider Controls (If multiple) -->
            <div v-if="offers.length > 1" class="absolute inset-y-0 right-0 pt-1 pr-1 flex items-start sm:pt-1 sm:pr-2 sm:items-start">
                <button @click="nextOffer" type="button" class="flex p-2 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-white">
                     <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../services/api'

const offers = ref([])
const currentIndex = ref(0)

const currentOffer = computed(() => {
    return offers.value[currentIndex.value] || {}
})

const fetchActiveOffers = async () => {
    try {
        const response = await api.get('/offers/active')
        offers.value = response.data
    } catch (error) {
        console.error("Failed to fetch active offers", error)
    }
}

const getDiscountText = (offer) => {
    if (offer.discount_type === 'percentage') {
        return `${parseInt(offer.discount_value)}%`
    }
    return `₹${parseInt(offer.discount_value)}`
}

const nextOffer = () => {
    if (offers.value.length === 0) return
    currentIndex.value = (currentIndex.value + 1) % offers.value.length
}

onMounted(() => {
    fetchActiveOffers()
    // Auto slide
    setInterval(() => {
        if(offers.value.length > 1) {
            nextOffer()
        }
    }, 5000)
})
</script>
