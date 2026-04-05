<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Tours Management</h2>
        <button @click="$emit('create')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Tour
        </button>
    </div>

    <!-- Filters -->
    <div class="flex gap-4 mb-6">
        <input v-model="searchQuery" type="text" placeholder="Search tours..." class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-white">
        <select v-model="filterStatus" class="block border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-white">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="sold_out">Sold Out</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="tour in filteredTours" :key="tour.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <img class="h-10 w-10 rounded-lg object-cover" :src="tour.images && tour.images[0] ? tour.images[0] : 'https://via.placeholder.com/100'" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ tour.title }}</div>
                                    <div class="text-xs text-gray-500">{{ tour.duration }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-900">{{ tour.destination_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₹{{ tour.price }}</div>
                            <div v-if="tour.sale_price" class="text-xs text-green-600 font-bold">Sale: ₹{{ tour.sale_price }}</div>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                :class="{
                                    'bg-green-100 text-green-800': tour.status === 'active',
                                    'bg-gray-100 text-gray-800': tour.status === 'inactive',
                                    'bg-red-100 text-red-800': tour.status === 'sold_out'
                                }">
                                {{ tour.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button @click="$emit('edit', tour)" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button @click="$emit('delete', tour.id)" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="filteredTours.length === 0" class="text-center py-12 text-gray-500">
                No tours found.
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    tours: {
        type: Array,
        required: true,
        default: () => []
    }
})

const emit = defineEmits(['create', 'edit', 'delete'])

const searchQuery = ref('')
const filterStatus = ref('')

const filteredTours = computed(() => {
    return props.tours.filter(tour => {
        const matchesSearch = tour.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                              tour.destination_name?.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchesStatus = filterStatus.value ? tour.status === filterStatus.value : true
        return matchesSearch && matchesStatus
    })
})
</script>
