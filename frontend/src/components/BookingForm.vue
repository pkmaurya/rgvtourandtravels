<template>
  <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 sticky top-24">
    <div class="border-l-4 border-brand-secondary pl-3 mb-6">
        <h3 class="text-xl font-bold text-gray-900">Booking Form</h3>
    </div>

    <!-- Price Section -->
    <div class="flex justify-between items-baseline mb-6 border-b border-gray-100 pb-4">
        <div>
            <span class="text-gray-500 text-sm block">Price</span>
            <span class="font-bold text-lg text-gray-900">From</span>
        </div>
        <div class="text-right">
             <div v-if="salePrice && salePrice < price" class="flex flex-col items-end">
                <span class="text-xs text-gray-400 line-through">₹{{ price }}</span>
                <span class="text-2xl font-bold text-gray-900">₹{{ salePrice }}</span>
            </div>
            <span v-else class="text-2xl font-bold text-gray-900">₹{{ price }}</span>
        </div>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Date Selection -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-900 mb-2">From Date</label>
                <div class="relative">
                    <input 
                        type="date" 
                        v-model="bookingData.startDate" 
                        :min="minDate"
                        @change="validateDates"
                        class="block w-full px-3 py-2 text-sm border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 rounded-md bg-gray-50 from-input"
                        required
                    >
                </div>
            </div>
            <div>
                 <label class="block text-sm font-bold text-gray-900 mb-2">To Date</label>
                <div class="relative">
                    <input 
                        type="date" 
                        v-model="bookingData.endDate" 
                        :min="bookingData.startDate || minDate"
                         @change="validateDates"
                        class="block w-full px-3 py-2 text-sm border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 rounded-md bg-gray-50 from-input"
                        required
                    >
                </div>
            </div>
        </div>

        <!-- Adults -->
        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
             <div>
                <span class="block text-sm font-bold text-gray-900">Adults</span>
                <span class="text-xs text-gray-500">Over 18 ( ₹{{ price }} )</span>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" @click="decrement('adults')" class="w-8 h-8 rounded-full bg-gray-600 text-white flex items-center justify-center hover:bg-gray-700 focus:outline-none disabled:opacity-50" :disabled="bookingData.adults <= 1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </button>
                <span class="font-bold w-4 text-center">{{ bookingData.adults }}</span>
                <button type="button" @click="increment('adults')" class="w-8 h-8 rounded-full bg-gray-600 text-white flex items-center justify-center hover:bg-gray-700 focus:outline-none">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </button>
            </div>
        </div>

        <!-- Children -->
        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
             <div>
                <span class="block text-sm font-bold text-gray-900">Children</span>
                <span class="text-xs text-gray-500">Under 18 ( ₹{{ childPrice }} )</span>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" @click="decrement('children')" class="w-8 h-8 rounded-full bg-gray-600 text-white flex items-center justify-center hover:bg-gray-700 focus:outline-none disabled:opacity-50" :disabled="bookingData.children <= 0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </button>
                <span class="font-bold w-4 text-center">{{ bookingData.children }}</span>
                <button type="button" @click="increment('children')" class="w-8 h-8 rounded-full bg-gray-600 text-white flex items-center justify-center hover:bg-gray-700 focus:outline-none">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full bg-brand-secondary text-white font-bold py-3 px-4 rounded-full hover:bg-brand-secondary/80 transition shadow-md hover:shadow-lg transform active:scale-95 flex justify-center items-center"
        >
            Booking Now For ₹{{ totalPrice }}
        </button>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits } from 'vue'

const props = defineProps({
    price: {
        type: Number,
        required: true
    },
    salePrice: {
        type: Number,
        default: null
    },
    tourId: {
        type: [String, Number],
        required: true
    }
})

const emit = defineEmits(['book'])

const bookingData = ref({
    startDate: '',
    endDate: '',
    adults: 2,
    children: 1
})

const minDate = computed(() => {
    const today = new Date()
    return today.toISOString().split('T')[0]
})

const validateDates = () => {
    if (bookingData.value.startDate && bookingData.value.endDate) {
        if (bookingData.value.endDate < bookingData.value.startDate) {
            bookingData.value.endDate = bookingData.value.startDate
        }
    }
}

const effectivePrice = computed(() => {
    return (props.salePrice && props.salePrice < props.price) ? props.salePrice : props.price
})

// Child price is 70% of adult price as a placeholder logic
const childPrice = computed(() => Math.round(effectivePrice.value * 0.7)) 

const totalPrice = computed(() => {
    return (bookingData.value.adults * effectivePrice.value) + (bookingData.value.children * childPrice.value)
})

const increment = (field) => {
    bookingData.value[field]++
}

const decrement = (field) => {
    if (field === 'adults' && bookingData.value.adults > 1) {
        bookingData.value.adults--
    } else if (field === 'children' && bookingData.value.children > 0) {
        bookingData.value.children--
    }
}

const handleSubmit = () => {
    emit('book', {
        start_date: bookingData.value.startDate,
        end_date: bookingData.value.endDate,
        adults: bookingData.value.adults,
        children: bookingData.value.children,
        totalPrice: totalPrice.value
    })
}
</script>

<style scoped>
.from-input::-webkit-calendar-picker-indicator {
    cursor: pointer;
}
</style>
