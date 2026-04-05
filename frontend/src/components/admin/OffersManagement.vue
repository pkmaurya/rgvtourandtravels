<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Offers & Discounts</h2>
            <button @click="openModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Offer
            </button>
        </div>

        <!-- Offers List -->
        <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
            <ul class="divide-y divide-gray-200">
                <li v-for="offer in offers" :key="offer.id" class="hover:bg-gray-50 transition-colors">
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Discount Badge -->
                            <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                <span v-if="offer.discount_type === 'percentage'">{{ parseInt(offer.discount_value) }}%</span>
                                <span v-else>₹{{ parseInt(offer.discount_value) }}</span>
                            </div>
                            
                            <div>
                                <p class="font-medium text-gray-900 text-lg">{{ offer.title }}</p>
                                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                                    <span :class="getStatusClass(offer.status)" class="px-2 py-0.5 rounded-full text-xs font-medium capitalize">
                                        {{ offer.status }}
                                    </span>
                                    <span v-if="offer.coupon_code" class="bg-gray-100 px-2 py-0.5 rounded font-mono text-gray-700">
                                        Code: {{ offer.coupon_code }}
                                    </span>
                                    <span>
                                        Valid: {{ formatDate(offer.start_date) }} - {{ formatDate(offer.end_date) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                             <button @click="openModal(offer)" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                             </button>
                             <button @click="deleteOffer(offer.id)" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                             </button>
                        </div>
                    </div>
                </li>
            </ul>
             <div v-if="offers.length === 0" class="text-center py-12 text-gray-500">
               No offers found. Create your first promotional offer!
            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <div v-if="showModal" class="fixed z-[9999] inset-0 overflow-y-auto" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="closeModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-[10000]">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-2xl font-serif font-bold text-gray-900 mb-6">
                                {{ isEditing ? 'Edit Offer' : 'Create New Offer' }}
                            </h3>
                            
                            <form @submit.prevent="saveOffer" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Offer Title</label>
                                        <input v-model="form.title" type="text" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50" placeholder="e.g. Summer Sale 20% Off">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                                        <select v-model="form.discount_type" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="fixed">Fixed Amount (₹)</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value</label>
                                        <input v-model="form.discount_value" type="number" step="0.01" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                        <input v-model="form.start_date" type="datetime-local" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                        <input v-model="form.end_date" type="datetime-local" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code (Optional)</label>
                                        <input v-model="form.coupon_code" type="text" placeholder="e.g. SUMMER20" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50 uppercase">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Booking Amount (₹)</label>
                                        <input v-model="form.min_booking_amount" type="number" min="0" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select v-model="form.status" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                     <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Scope</label>
                                        <select v-model="form.scope" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                            <option value="all">All Tours</option>
                                            <option value="specific_tours" disabled>Specific Tours (Coming Soon)</option>
                                            <option value="specific_destinations" disabled>Specific Destinations (Coming Soon)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <RichTextEditor v-model="form.description" />
                                    </div>
                                </div>

                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                        {{ isEditing ? 'Update Offer' : 'Create Offer' }}
                                    </button>
                                    <button @click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import RichTextEditor from '../RichTextEditor.vue'

const offers = ref([])
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)

const form = ref({
    title: '',
    description: '',
    discount_type: 'percentage',
    discount_value: '',
    coupon_code: '',
    start_date: '',
    end_date: '',
    min_booking_amount: 0,
    status: 'active',
    scope: 'all'
})

const fetchOffers = async () => {
    try {
        const response = await api.get('/offers')
        offers.value = response.data
    } catch (error) {
        console.error("Failed to fetch offers", error)
    }
}

const openModal = (offer = null) => {
    if (offer) {
        isEditing.value = true
        editingId.value = offer.id
        form.value = {
            title: offer.title,
            description: offer.description,
            discount_type: offer.discount_type,
            discount_value: offer.discount_value,
            coupon_code: offer.coupon_code,
            start_date: offer.start_date.replace(' ', 'T'), // Format for datetime-local
            end_date: offer.end_date.replace(' ', 'T'),
            min_booking_amount: offer.min_booking_amount,
            status: offer.status,
            scope: offer.scope
        }
    } else {
        isEditing.value = false
        editingId.value = null
        form.value = {
            title: '',
            description: '',
            discount_type: 'percentage',
            discount_value: '',
            coupon_code: '',
            start_date: '',
            end_date: '',
            min_booking_amount: 0,
            status: 'active',
            scope: 'all'
        }
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}

const saveOffer = async () => {
    try {
        const payload = { ...form.value }
        if (isEditing.value) {
            await api.put(`/offers/${editingId.value}`, payload)
        } else {
            await api.post('/offers', payload)
        }
        closeModal()
        fetchOffers()
    } catch (error) {
        console.error("Failed to save offer", error)
        alert("Failed to save offer: " + (error.response?.data?.message || error.message))
    }
}

const deleteOffer = async (id) => {
    if(confirm("Are you sure you want to delete this offer?")) {
        try {
            await api.delete(`/offers/${id}`)
            fetchOffers()
        } catch (error) {
            console.error("Failed to delete offer", error)
            alert("Failed to delete offer")
        }
    }
}

const getStatusClass = (status) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-800' 
        : 'bg-gray-100 text-gray-800'
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    return new Date(dateString).toLocaleDateString(undefined, {
        day: 'numeric', month: 'short', year: 'numeric'
    })
}

onMounted(() => {
    fetchOffers()
})
</script>
