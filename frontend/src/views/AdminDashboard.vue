<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <AdminSidebar :activeTab="activeTab" :isCollapsed="isSidebarCollapsed" @navigate="activeTab = $event" @logout="logout" @toggle="toggleSidebar" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col transition-all duration-300" :class="[isSidebarCollapsed ? 'md:ml-20' : 'md:ml-64']">
      <!-- Header -->
      <AdminHeader :user="authStore.user" @navigate="activeTab = $event" @logout="logout" />

      <!-- Main Section -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 mt-16">
        
        <!-- Dashboard Tab -->
        <div v-if="activeTab === 'dashboard'" class="space-y-6">
            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <DashboardStats title="Pending" :value="stats.pending_bookings.toString()" subtext="Total pending" colorClass="bg-indigo-50 text-indigo-600">
                    <template #icon>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                </DashboardStats>
                 <DashboardStats title="Earnings" :value="`₹${stats.total_earnings.toLocaleString()}`" subtext="Total earnings" colorClass="bg-green-50 text-green-600">
                    <template #icon>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                </DashboardStats>
                 <DashboardStats title="Bookings" :value="stats.total_bookings.toString()" subtext="Total bookings" colorClass="bg-blue-50 text-blue-600">
                    <template #icon>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </template>
                </DashboardStats>
                 <DashboardStats title="Services" :value="stats.total_services.toString()" subtext="Total bookable services" colorClass="bg-purple-50 text-purple-600">
                    <template #icon>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </template>
                </DashboardStats>
            </div>

            <!-- Charts & Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <EarningChart :chartData="chartData" />
                <RecentBookings :bookings="recentBookings" />
            </div>
        </div>

        <!-- Tours Management Tab -->
        <div v-if="activeTab === 'tours'" class="space-y-6">
            <AdminTours 
                :tours="tours" 
                @create="openModal()" 
                @edit="openModal" 
                @delete="deleteTour" 
            />
        </div>

            <!-- Bookings Tab -->
        <div v-if="activeTab === 'bookings'" class="space-y-6">
             <BookingHistory />
        </div>

        <!-- Users Tab -->
        <div v-if="activeTab === 'users'" class="space-y-6">
            <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
            <UserManagement />
        </div>

        <!-- Destinations Tab -->
        <div v-if="activeTab === 'destinations'" class="space-y-6">
            <DestinationsManagement />
        </div>

        <!-- Offers Tab -->
        <div v-if="activeTab === 'offers'" class="space-y-6">
            <OffersManagement />
        </div>

        <!-- Payments Tab -->
        <div v-if="activeTab === 'payments'" class="space-y-6">
            <PaymentsManagement />
        </div>

        <!-- Inquiries Tab -->
        <div v-if="activeTab === 'inquiries'" class="space-y-6">
            <ContactManagement />
        </div>

        <!-- Chatbot Tab -->
        <div v-if="activeTab === 'chatbot'" class="space-y-6">
            <ChatbotManagement />
        </div>

        <!-- Notifications Tab -->
         <div v-if="activeTab === 'notifications'" class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Notifications</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-gray-400 bg-gray-50 p-4 rounded">
                         <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">
                                    No new notifications system.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tab -->
        <div v-if="activeTab === 'settings'" class="space-y-6">
            <AdminSettings />
        </div>
      </main>
    </div>

    <!-- Modal -->
    <Teleport to="body">
        <TourForm 
            v-if="showModal" 
            :tour="selectedTour" 
            :destinations="destinations" 
            @close="closeModal" 
            @saved="onTourSaved" 
        />
    </Teleport>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import AdminSidebar from '../components/admin/AdminSidebar.vue'
import AdminHeader from '../components/admin/AdminHeader.vue'
import DashboardStats from '../components/admin/DashboardStats.vue'
import EarningChart from '../components/admin/EarningChart.vue'
import RecentBookings from '../components/admin/RecentBookings.vue'
import BookingHistory from '../components/admin/BookingHistory.vue'
import UserManagement from '../components/admin/UserManagement.vue'
import DestinationsManagement from '../components/admin/DestinationsManagement.vue'
import OffersManagement from '../components/admin/OffersManagement.vue'
import AdminTours from '../components/admin/AdminTours.vue'
import TourForm from '../components/admin/TourForm.vue'
import PaymentsManagement from '../components/admin/PaymentsManagement.vue'
import AdminSettings from '../components/admin/AdminSettings.vue'
import ContactManagement from '../components/admin/ContactManagement.vue'
import ChatbotManagement from '../components/admin/ChatbotManagement.vue'

const authStore = useAuthStore()
const router = useRouter()
const activeTab = ref('dashboard')
const tours = ref([])
const destinations = ref([])
const showModal = ref(false)
const selectedTour = ref(null)
const isSidebarCollapsed = ref(false)

const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value
}

// Dynamic Data Refs
const stats = ref({
    total_bookings: 0,
    total_earnings: 0,
    pending_bookings: 0,
    total_services: 0
})
const recentBookings = ref([])

const logout = () => {
    authStore.logout()
    router.push('/admin/login')
}

const fetchStats = async () => {
    try {
        const response = await api.get('/dashboard/stats')
        stats.value = response.data
    } catch (error) {
        console.error("Failed to fetch dashboard stats", error)
    }
}

const fetchRecentBookings = async () => {
    try {
        const response = await api.get('/dashboard/recent-bookings')
        recentBookings.value = response.data.data
    } catch (error) {
        console.error("Failed to fetch recent bookings", error)
    }
}

const chartData = ref(null)

const fetchChartData = async () => {
    try {
        const response = await api.get('/dashboard/chart?days=6')
        // Add styling to datasets
        const datasets = response.data.datasets.map(ds => ({
            ...ds,
            backgroundColor: 'rgba(79, 70, 229, 0.1)',
            borderColor: '#4F46E5',
            borderWidth: 2,
            pointBackgroundColor: '#4F46E5',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#4F46E5',
            fill: true,
            tension: 0.4
        }))
        
        chartData.value = {
            labels: response.data.labels,
            datasets: datasets
        }
    } catch (error) {
        console.error("Failed to fetch chart data", error)
    }
}

const fetchDestinations = async () => {
    try {
        const response = await api.get('/destinations')
        destinations.value = response.data
    } catch (error) {
        console.error("Failed to fetch destinations", error)
    }
}

const fetchTours = async () => {
    try {
        const response = await api.get('/tours?page=1&limit=100')
        if(response.data.data) {
             tours.value = response.data.data
        } else {
             tours.value = response.data
        }
    } catch (error) {
        console.error("Failed to fetch tours", error)
    }
}

const openModal = async (tour = null) => {
    // Refresh destinations to ensure we have the latest list
    await fetchDestinations()
    selectedTour.value = tour
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    selectedTour.value = null
}

const onTourSaved = () => {
    closeModal()
    fetchTours()
    fetchDestinations() // Also good practice to refresh
}

const deleteTour = async (id) => {
    if(confirm("Are you sure you want to delete this tour?")) {
        try {
            await api.delete(`/tours/${id}`)
            fetchTours()
        } catch (error) {
            console.error("Failed to delete tour", error)
            alert("Failed to delete tour")
        }
    }
}

onMounted(() => {
    fetchStats()
    fetchRecentBookings()
    fetchChartData()
    fetchTours()
    fetchDestinations()
})
</script>
