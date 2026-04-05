<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Recent Bookings</h3>
        <a href="#" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(booking, index) in bookings" :key="booking.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #{{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                             <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-lg object-cover" :src="booking.image" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ booking.title }}</div>
                                <div class="text-xs text-gray-500">{{ booking.location }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        ${{ booking.total }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(booking.status)}`">
                            {{ booking.status }}
                        </span>
                    </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ booking.date }}<br>
                        <span v-if="booking.start_date" class="text-xs text-gray-400">
                            {{ booking.start_date }} - {{ booking.end_date }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>

<script setup>
defineProps({
    bookings: Array
})

const getStatusColor = (status) => {
    switch(status.toLowerCase()) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'confirmed': return 'bg-blue-100 text-blue-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}
</script>
