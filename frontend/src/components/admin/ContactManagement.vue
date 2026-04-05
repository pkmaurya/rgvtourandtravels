<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Inquiries</h2>
        <p class="text-sm text-gray-500">View and manage messages from the "Contact Us" form.</p>
    </div>

    <!-- Table Container -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Subject</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Message</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="contact in contacts" :key="contact.id" class="hover:bg-gray-50 transition-colors">
                         <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-900">{{ formatDate(contact.created_at) }}</div>
                         </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ contact.name }}</div>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-500">{{ contact.email }}</div>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                             <div class="text-sm text-gray-900">{{ contact.subject }}</div>
                         </td>
                         <td class="px-6 py-4">
                             <div class="text-sm text-gray-500 max-w-xs truncate" :title="contact.message">{{ contact.message }}</div>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                             <button @click="deletecontact(contact.id)" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                         </td>
                    </tr>
                </tbody>
            </table>
        </div>

         <div v-if="contacts.length === 0" class="text-center py-12 text-gray-500">
             No inquiries found.
         </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const contacts = ref([])

const fetchContacts = async () => {
    try {
        const response = await api.get('/admin/contacts')
        contacts.value = response.data
    } catch (error) {
        console.error("Failed to fetch contacts", error)
    }
}

const deletecontact = async (id) => {
    if(!confirm('Are you sure you want to delete this message?')) return

    try {
        await api.delete(`/admin/contacts/${id}`)
        fetchContacts() // Refresh list
    } catch (error) {
        console.error("Failed to delete contact", error)
        alert("Failed to delete message.")
    }
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('en-US', { month: '2-digit', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(date)
}

onMounted(() => {
    fetchContacts()
})
</script>
