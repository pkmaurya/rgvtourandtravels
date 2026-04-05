<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Booking History</h2>
        <p class="text-sm text-gray-500">View and manage all your booking transactions.</p>
    </div>

    <!-- Tabs -->
    <div class="mb-6 overflow-x-auto">
        <nav class="flex space-x-4 border-b border-gray-200" aria-label="Tabs">
            <button 
                v-for="tab in tabs" 
                :key="tab.value"
                @click="currentTab = tab.value"
                :class="[
                    currentTab === tab.value 
                    ? 'border-indigo-500 text-indigo-600' 
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
                ]"
            >
                {{ tab.name }}
            </button>
        </nav>
    </div>

    <!-- Table Container -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        <!-- Search & Filter (Optional, can add later if needed) -->
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Travel Dates</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Guests</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Paid</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Remain</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                             <div class="flex items-center">
                                 <span class="text-xs font-medium text-gray-500">{{ booking.type }}</span>
                             </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ booking.title }}</div>
                            <div class="text-xs text-gray-500">{{ booking.user.name }}</div>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-900">{{ formatDate(booking.order_date) }}</div>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                             <div v-if="booking.start_date">
                                 <div class="text-xs text-gray-500">From: <span class="text-gray-900 font-medium">{{ formatDate(booking.start_date) }}</span></div>
                                 <div class="text-xs text-gray-500">To: <span class="text-gray-900 font-medium">{{ formatDate(booking.end_date) }}</span></div>
                             </div>
                             <div v-else class="text-xs text-gray-400">N/A</div>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-900">{{ booking.adults }} Adults, {{ booking.children }} Kids</div>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                             ₹{{ booking.total }}
                         </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                              <span :class="`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(booking.status)}`">
                                 {{ booking.status }}
                             </span>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                             N/A
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                             N/A
                         </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                             <div class="relative inline-block text-left">
                                <Menu as="div" class="relative inline-block text-left">
                                    <div>
                                        <MenuButton class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                                            Actions
                                            <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5" aria-hidden="true" />
                                        </MenuButton>
                                    </div>
                                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                            <div class="py-1">
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="openModal(booking)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm']">
                                                        View Details
                                                    </button>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="updateStatus(booking.id, 'confirmed')" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm']">
                                                        Mark as Confirmed
                                                    </button>
                                                </MenuItem>
                                                 <MenuItem v-slot="{ active }">
                                                    <button @click="updateStatus(booking.id, 'completed')" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm']">
                                                        Mark as Completed
                                                    </button>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="updateStatus(booking.id, 'cancelled')" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm text-red-600']">
                                                        Cancel Booking
                                                    </button>
                                                </MenuItem>
                                                 <MenuItem v-slot="{ active }">
                                                    <button @click="deleteBooking(booking.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm text-red-600 font-bold']">
                                                        Delete
                                                    </button>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                             </div>
                         </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6" v-if="meta.last_page > 1">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                     <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ (meta.current_page - 1) * meta.per_page + 1 }}</span> to <span class="font-medium">{{ Math.min(meta.current_page * meta.per_page, meta.total) }}</span> of <span class="font-medium">{{ meta.total }}</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                         <button 
                            @click="changePage(meta.current_page - 1)" 
                            :disabled="meta.current_page === 1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button 
                            v-for="page in meta.last_page" 
                            :key="page"
                            @click="changePage(page)"
                            :class="[
                                page === meta.current_page 
                                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' 
                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                            ]"
                        >
                            {{ page }}
                        </button>
                         <button 
                            @click="changePage(meta.current_page + 1)" 
                            :disabled="meta.current_page === meta.last_page"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span class="sr-only">Next</span>
                             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
         <div v-else-if="bookings.length === 0" class="text-center py-12 text-gray-500">
             No bookings found for the selected status.
         </div>
    </div>

    <!-- Details Modal -->
    <div v-if="showDetailsModal && selectedBooking" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
             <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>
             <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
             
             <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Booking Details #{{ selectedBooking.id }}
                            </h3>
                            <div class="mt-4 border-t border-gray-200 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tour</p>
                                    <p class="text-base text-gray-900">{{ selectedBooking.title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                    <span :class="`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(selectedBooking.status)}`">
                                        {{ selectedBooking.status }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Customer</p>
                                    <p class="text-sm text-gray-900">{{ selectedBooking.user?.name || 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ selectedBooking.user?.email || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Dates</p>
                                    <p class="text-sm text-gray-900">{{ formatDate(selectedBooking.check_in) }} - {{ formatDate(selectedBooking.check_out) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Guests</p>
                                    <p class="text-sm text-gray-900">{{ selectedBooking.adults }} Adults, {{ selectedBooking.children }} Children</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Price</p>
                                    <p class="text-lg font-bold text-gray-900">₹{{ selectedBooking.total }}</p>
                                </div>
                            </div>

                             <div class="mt-6 flex gap-3">
                                 <button v-if="selectedBooking.status === 'pending'" @click="updateStatus(selectedBooking.id, 'confirmed')" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">Confirm Booking</button>
                                 <button v-if="['pending', 'confirmed'].includes(selectedBooking.status.toLowerCase())" @click="updateStatus(selectedBooking.id, 'cancelled')" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">Cancel Booking</button>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
             </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import api from '../../services/api'

const bookings = ref([])
const meta = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    per_page: 10
})
const currentTab = ref('all')
const showDetailsModal = ref(false)
const selectedBooking = ref(null)

const tabs = [
    { name: 'All Booking', value: 'all' },
    { name: 'Completed', value: 'completed' },
    { name: 'Pending', value: 'pending' },
    { name: 'Confirmed', value: 'confirmed' },
    { name: 'Cancelled', value: 'cancelled' },
    { name: 'Paid', value: 'paid' },
    { name: 'Unpaid', value: 'unpaid' }
]

const fetchBookings = async (page = 1) => {
    try {
        const response = await api.get('/admin/bookings', { // Use admin endpoint
            params: {
                page,
                limit: 10,
                status: currentTab.value
            }
        })
        bookings.value = response.data.data
        meta.value = response.data.meta
    } catch (error) {
        console.error("Failed to fetch bookings", error)
    }
}

const changePage = (page) => {
    if (page >= 1 && page <= meta.value.last_page) {
        fetchBookings(page)
    }
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' }).format(date)
}

const getStatusColor = (status) => {
    if(!status) return 'bg-gray-100 text-gray-800'
    switch(status.toLowerCase()) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'confirmed': return 'bg-blue-100 text-blue-800';
        case 'completed': return 'bg-green-100 text-green-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

const openModal = (booking) => {
    selectedBooking.value = booking
    showDetailsModal.value = true
}

const closeModal = () => {
    showDetailsModal.value = false
    selectedBooking.value = null
}

const updateStatus = async (id, status) => {
    if(!confirm(`Are you sure you want to mark this booking as ${status}?`)) return

    try {
        await api.put(`/admin/bookings/${id}/status`, { status })
        fetchBookings(meta.value.current_page)
        if (showDetailsModal.value) closeModal()
    } catch (error) {
        console.error("Failed to update status", error)
        alert("Failed to update status.")
    }
}

const deleteBooking = async (id) => {
    if(!confirm("Are you sure you want to DELETE this booking? This action cannot be undone.")) return

    try {
        await api.delete(`/admin/bookings/${id}`)
        fetchBookings(meta.value.current_page)
    } catch (error) {
        console.error("Failed to delete booking", error)
        alert("Failed to delete booking.")
    }
}

watch(currentTab, () => {
    fetchBookings(1)
})

onMounted(() => {
    fetchBookings()
})
</script>
