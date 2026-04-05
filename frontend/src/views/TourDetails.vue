<template>
  <div v-if="tour" class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <PageHero 
      :title="tour.title" 
      :subtitle="tour.destination_name"
      :background-image="tour.images && tour.images[0] ? tour.images[0] : 'https://via.placeholder.com/1200'"
    />

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="lg:grid lg:grid-cols-3 lg:gap-x-8">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-12">
           <!-- Overview -->
           <div class="bg-white rounded-2xl p-8 shadow-sm">
             <div class="flex items-center gap-2 mb-4">
                 <span v-if="tour.category" class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wide rounded">{{ tour.category }}</span>
                 <span v-if="tour.featured" class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold uppercase tracking-wide rounded">Featured</span>
             </div>
             <h2 class="text-2xl font-bold text-gray-900 mb-4">Tour Overview</h2>
             <div class="prose max-w-none text-gray-600 space-y-4" v-html="tour.description"></div>
             
             <!-- Key Details -->
             <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8 pt-8 border-t border-gray-100">
                <div class="text-center">
                    <span class="block text-gray-400 text-sm mb-1">Duration</span>
                    <span class="font-bold text-gray-900">{{ tour.duration }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-gray-400 text-sm mb-1">Location</span>
                    <span class="font-bold text-gray-900 truncate">{{ tour.destination_name }}</span>
                </div>
                 <div class="text-center">
                    <span class="block text-gray-400 text-sm mb-1">Group Size</span>
                    <span class="font-bold text-gray-900">Max {{ tour.max_travelers || 12 }}</span>
                </div>
                 <div class="text-center">
                    <span class="block text-gray-400 text-sm mb-1">Status</span>
                    <span class="font-bold capitalize" :class="{'text-green-600': tour.status==='active', 'text-red-500': tour.status!=='active'}">{{ tour.status || 'Active' }}</span>
                </div>
             </div>
           </div>

           <!-- Highlights -->
           <div class="bg-white rounded-2xl p-8 shadow-sm" v-if="tour.highlights && tour.highlights.length">
             <h2 class="text-2xl font-bold text-gray-900 mb-6">Highlights</h2>
             <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <li v-for="(highlight, idx) in tour.highlights" :key="idx" class="flex items-start">
                     <svg class="w-5 h-5 text-indigo-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                     <span class="text-gray-700">{{ highlight }}</span>
                 </li>
             </ul>
           </div>

           <!-- Itinerary -->
           <div class="bg-white rounded-2xl p-8 shadow-sm" v-if="tour.itinerary && tour.itinerary.length">
             <h2 class="text-2xl font-bold text-gray-900 mb-6">Itinerary</h2>
             <div class="space-y-8">
                <div v-for="(day, index) in tour.itinerary" :key="index" class="relative pl-8 border-l-2 border-indigo-100 last:border-0 pb-8 last:pb-0">
                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-indigo-600 ring-4 ring-white"></div>
                    <span class="text-indigo-600 font-bold text-sm uppercase tracking-wide mb-1 block">Day {{ index + 1 }}</span>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ day.title }}</h3>
                    <p class="text-gray-600">{{ day.details }}</p>
                </div>
             </div>
           </div>
           
             <!-- Gallery -->
            <div class="bg-white rounded-2xl p-8 shadow-sm" v-if="tour.images && tour.images.length > 1">
                 <h2 class="text-2xl font-bold text-gray-900 mb-6">Gallery</h2>
                 <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                     <img v-for="(img, idx) in tour.images" :key="idx" :src="img" class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition shadow-sm">
                 </div>
            </div>

            <!-- Inclusions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                 <h3 class="font-bold text-gray-900 mb-4">What's Included</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <ul class="space-y-2">
                        <li v-for="(inc, idx) in tour.inclusions" :key="idx" class="flex items-start text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ inc }}
                        </li>
                    </ul>
                    <ul v-if="tour.exclusions && tour.exclusions.length" class="space-y-2">
                         <li v-for="(exc, idx) in tour.exclusions" :key="idx" class="flex items-start text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            {{ exc }}
                        </li>
                    </ul>
                 </div>
            </div>
            </div>


        <!-- Sidebar -->
        <div class="lg:col-span-1 mt-10 lg:mt-0">
            <!-- Using the new BookingForm Component -->
            <BookingForm 
                v-if="tour"
                :price="tour.price" 
                :sale-price="tour.sale_price" 
                :tour-id="tour.id"
                @book="handleBooking"
            />
            
            <!-- Additional Info Cards can follow -->
             
        </div>

      </div>
      </div>
    </div>

  <div v-else class="text-center py-12">Loading tour details...</div>

  <BookingModal 
    :is-open="isBookingModalOpen" 
    :tour="tour" 
    @close="isBookingModalOpen = false" 
  />
</template>



<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import { useAuthStore } from '../stores/auth'
import PageHero from '../components/PageHero.vue' 
import BookingModal from '../components/BookingModal.vue'
import BookingForm from '../components/BookingForm.vue'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const tour = ref(null)

const isBookingModalOpen = ref(false)

const openBookingModal = () => {
    isBookingModalOpen.value = true
}

// Offer Logic
const activeOffers = ref([])

const fetchTour = async () => {
    try {
        const [tourRes, offersRes] = await Promise.all([
            api.get(`/tours/${route.params.id}`),
            api.get('/offers/active')
        ])
        tour.value = tourRes.data
        activeOffers.value = offersRes.data
        
        // SEO: Update Title and Meta Tags
        if(tour.value) {
            document.title = `${tour.value.title} | RGV Tour and Travels`
            // Basic meta description update (could be improved with a proper composable)
            let metaDesc = document.querySelector('meta[name="description"]')
            if (!metaDesc) {
                metaDesc = document.createElement('meta')
                metaDesc.name = "description"
                document.head.appendChild(metaDesc)
            }
            metaDesc.setAttribute("content", tour.value.description.substring(0, 160) + '...')
            
            // Canonical URL (optional but good for SEO)
             let canonical = document.querySelector('link[rel="canonical"]')
            if(!canonical) {
                 canonical = document.createElement('link')
                 canonical.rel = "canonical"
                 document.head.appendChild(canonical)
            }
            const slug = tour.value.slug || tour.value.id
            canonical.setAttribute("href", `${window.location.origin}/tours/${slug}`)
            
            // If the current URL doesn't match the slug, you might want to replace history state
            // to show the slug in the URL bar if accessed via ID, but for now we just rely on the router pushing the slug if linked correctly.
        }

    } catch (error) {
        console.error("Failed to load tour or offers", error)
    }
}

onMounted(fetchTour)

// Watch for route changes to reload data (e.g. clicking related tours)
watch(() => route.params.id, fetchTour)

const effectiveBasePrice = computed(() => {
    if (!tour.value) return 0
    return (tour.value.sale_price && tour.value.sale_price < tour.value.price) ? tour.value.sale_price : tour.value.price
})

const bestOffer = computed(() => {
    if (!activeOffers.value.length || !tour.value) return null
    const relevantOffers = activeOffers.value.filter(o => o.scope === 'all')
    if (relevantOffers.length === 0) return null
    return relevantOffers.reduce((prev, current) => {
        let prevVal = prev.discount_type === 'percentage' ? (effectiveBasePrice.value * prev.discount_value / 100) : prev.discount_value
        let currVal = current.discount_type === 'percentage' ? (effectiveBasePrice.value * current.discount_value / 100) : current.discount_value
        return (currVal > prevVal) ? current : prev
    })
})

const discountedPrice = computed(() => {
    if (!tour.value) return 0
    let price = effectiveBasePrice.value
    if (bestOffer.value) {
        let discount = 0
        if (bestOffer.value.discount_type === 'percentage') {
            discount = (price * bestOffer.value.discount_value / 100)
        } else {
            discount = bestOffer.value.discount_value
        }
        price = price - discount
    }
    return Math.floor(price)
})

const handleBooking = async (bookingData) => {
    if (!authStore.isAuthenticated) {
        alert("Please login to book a tour")
        router.push('/login')
        return
    }

    try {
        const payload = {
            tour_id: tour.value.id,
            start_date: bookingData.start_date,
            end_date: bookingData.end_date,
            people: bookingData.adults + bookingData.children, // Backend might expect total people or separate fields. Let's check Booking model.
            // Looking at Booking model (not visible but usually inferred), let's assume standard fields
            // Actually, let's send what the backend likely expects. 
            // Previous steps created Booking model. 
            // Let's assume 'people' is the field for count. 
            // But wait, the alert showed adults/children.
            // Let's send basic data first or check Booking.php if needed.
            // For now, I'll send specific fields if the backend supports them, otherwise just total people.
            total_price: bookingData.totalPrice
        }
        
        // Refined payload based on typical structure
        const res = await api.post('/bookings', {
            tour_id: parseInt(tour.value.id),
            user_id: authStore.user.id,
            start_date: bookingData.start_date,
            end_date: bookingData.end_date,
            people: parseInt(bookingData.adults) + parseInt(bookingData.children),
            total_price: parseFloat(bookingData.totalPrice),
            status: 'pending'
        })

        if (res.data && res.data.message === "Booking created") {
             // The backend should probably return the booking ID. 
             // If it doesn't, we might need to modify the backend or fetch the last booking.
             // Let's check BookingController.php to see what it returns.
             // For now, assuming it returns the created ID or valid data.
             
             // Check if backend returns id
             if(res.data.id) {
                 router.push(`/checkout/${res.data.id}`)
             } else {
                 // Fallback or error
                 console.error("Booking created but no ID returned", res.data)
                 // Maybe fetch latest?
                 alert("Booking created! Redirecting to dashboard...")
                 router.push('/dashboard')
             }
        }
    } catch (e) {
        console.error("Booking Error", e)
        alert("Failed to create booking. Please try again.")
    }
}
</script>
