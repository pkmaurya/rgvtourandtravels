<template>
  <div class="bg-gray-50 min-h-screen">
    <PageHero 
      title="Our Exclusive Tours" 
      subtitle="Discover the world with our premium tour packages designed for unforgettable experiences."
      background-image="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1"
    />

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filter -->
        <aside class="w-full lg:w-1/4 flex-shrink-0">
          <TourFilter @filter-change="handleFilterChange" />
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
          <div class="flex justify-between items-center mb-6">
             <p class="text-gray-500">Showing {{ tours.length }} of {{ totalItems }} Tours</p> 
             <!-- Sort or other controls could go here -->
          </div>

          <div v-if="loading" class="text-center py-12">Loading...</div>

          <div v-else>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <TourCard 
                v-for="tour in tours" 
                :key="tour.id"
                :id="tour.id"
                :slug="tour.slug"
                :title="tour.title"
                :price="tour.price"
                :image="tour.images && tour.images[0] ? tour.images[0] : 'https://via.placeholder.com/400'"
                :location="tour.location || tour.destination_name || 'Unknown Location'"
                :featured="!!tour.featured"
                :duration="tour.duration"
                :description="tour.description"
              />
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex justify-center mt-8">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button 
                        @click="changePage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L8.414 10l4.293 4.293a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <button 
                        v-for="page in totalPages" 
                        :key="page"
                        @click="changePage(page)"
                        :class="[
                            page === currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]"
                    >
                        {{ page }}
                    </button>

                    <button 
                        @click="changePage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L11.586 10 7.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </nav>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import TourFilter from '../components/TourFilter.vue'
import TourCard from '../components/TourCard.vue'
import PageHero from '../components/PageHero.vue'

const route = useRoute()
const tours = ref([])
const loading = ref(true)
const currentPage = ref(1)
const totalPages = ref(1)
const totalItems = ref(0)
const itemsPerPage = 10
const activeFilters = ref({})

const fetchTours = async () => {
  loading.value = true
  try {
    // Build query params
    let query = `/tours?page=${currentPage.value}&limit=${itemsPerPage}`
    
    // Merge activeFilters with route query params
    const filters = { ...activeFilters.value, ...route.query }

    if (filters.destination_id) query += `&destination_id=${filters.destination_id}`
    if (filters.category) query += `&category=${filters.category}`
    if (filters.max_price) query += `&max_price=${filters.max_price}`
    
    // Add search params from URL
    if (filters.q) query += `&q=${filters.q}`
    if (filters.duration) query += `&duration=${filters.duration}`
    if (filters.start) query += `&start_date=${filters.start}`

    const response = await api.get(query)
    
    // Check if response has pagination wrapper or is a direct array (legacy support)
    if (response.data.data) {
        tours.value = response.data.data
        const pagination = response.data.pagination
        currentPage.value = pagination.current_page
        totalPages.value = pagination.total_pages
        totalItems.value = pagination.total_items
    } else {
        // Fallback for flat array response (if backend hasn't updated or different endpoint)
        tours.value = response.data
        totalItems.value = tours.value.length
        totalPages.value = 1
    }

  } catch (error) {
    console.error("Failed to load tours", error)
     // Fallback mock data if API fails
        tours.value = []
        totalItems.value = 0
  } finally {
      loading.value = false
  }
}

const handleFilterChange = (filters) => {
    activeFilters.value = filters
    currentPage.value = 1 // Reset to first page
    fetchTours()
}

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
        // Scroll to top of list
        window.scrollTo({ top: 300, behavior: 'smooth' })
        fetchTours()
    }
}

// Watch for route query changes (e.g. from Hero search)
watch(() => route.query, () => {
    currentPage.value = 1
    fetchTours()
})

onMounted(() => {
  fetchTours()
})
</script>
