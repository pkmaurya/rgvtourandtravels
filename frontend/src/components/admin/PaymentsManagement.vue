<template>
  <div class="max-w-7xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
      </div>

      <!-- Filters -->
      <div class="bg-white p-4 rounded-lg shadow mb-6 flex flex-wrap gap-4">
          <select v-model="filterGateway" @change="fetchPayments" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
              <option value="all">All Gateways</option>
              <option value="razorpay">Razorpay</option>
              <option value="stripe">Stripe</option>
          </select>
          
          <select v-model="filterStatus" @change="fetchPayments" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
              <option value="all">All Statuses</option>
              <option value="paid">Paid</option>
              <option value="pending">Pending</option>
              <option value="failed">Failed</option>
              <option value="refunded">Refunded</option>
          </select>

          <button @click="fetchPayments" class="ml-auto bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
              Refresh
          </button>
      </div>

      <!-- Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                  <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="payment in payments" :key="payment.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ payment.id }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {{ payment.user_name || 'Guest' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          #{{ payment.booking_id }} <br/>
                          <span class="text-xs text-gray-400 max-w-xs truncate block" :title="payment.tour_title">{{ payment.tour_title }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                          {{ payment.currency === 'usd' ? '$' : '₹' }}{{ payment.amount }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ payment.gateway }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">{{ payment.transaction_id || payment.order_id }}</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                                :class="{
                                    'bg-green-100 text-green-800': payment.status === 'paid',
                                    'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                    'bg-red-100 text-red-800': payment.status === 'failed',
                                    'bg-gray-100 text-gray-800': payment.status === 'refunded'
                                }">
                              {{ payment.status }}
                          </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ new Date(payment.created_at).toLocaleDateString() }}
                      </td>
                  </tr>
                  <tr v-if="payments.length === 0">
                      <td colspan="8" class="px-6 py-4 text-center text-gray-500">No payments found.</td>
                  </tr>
              </tbody>
          </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between items-center" v-if="totalPages > 1">
          <button 
              @click="changePage(page - 1)" 
              :disabled="page === 1"
              class="bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50"
          >
              Previous
          </button>
          <span class="text-sm text-gray-700">
              Page {{ page }} of {{ totalPages }}
          </span>
          <button 
               @click="changePage(page + 1)" 
               :disabled="page === totalPages"
               class="bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50"
          >
              Next
          </button>
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import { useRouter } from 'vue-router'

const router = useRouter()

const payments = ref([])
const page = ref(1)
const totalPages = ref(1)
const filterGateway = ref('all')
const filterStatus = ref('all')

const fetchPayments = async () => {
  try {
      const res = await api.get('/admin/payments', {
          params: {
              page: page.value,
              gateway: filterGateway.value,
              status: filterStatus.value
          }
      })
      payments.value = res.data.data
      page.value = res.data.meta.current_page
      totalPages.value = res.data.meta.last_page
  } catch (e) {
      console.error("Failed to fetch payments", e)
      if (e.response && e.response.status === 401) {
          router.push('/admin/login')
      }
  }
}

const changePage = (newPage) => {
  if (newPage >= 1 && newPage <= totalPages.value) {
      page.value = newPage
      fetchPayments()
  }
}

onMounted(() => {
  fetchPayments()
})
</script>
