<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Chatbot Management</h2>
            <p class="text-sm text-gray-500">Manage Q&A for the website chatbot.</p>
        </div>
        <button @click="openModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Q&A
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
        
        <!-- Tabs / Filters -->
         <div class="border-b border-gray-200 px-6 py-3 bg-gray-50 flex gap-4">
            <button @click="filterCategory = 'all'" :class="['text-sm font-medium px-3 py-1 rounded-md transition-colors', filterCategory === 'all' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']">All</button>
            <button @click="filterCategory = 'tour'" :class="['text-sm font-medium px-3 py-1 rounded-md transition-colors', filterCategory === 'tour' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']">Tours</button>
            <button @click="filterCategory = 'support'" :class="['text-sm font-medium px-3 py-1 rounded-md transition-colors', filterCategory === 'support' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']">Support</button>
            <button @click="filterCategory = 'general'" :class="['text-sm font-medium px-3 py-1 rounded-md transition-colors', filterCategory === 'general' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']">General</button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Question</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Answer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                         <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="qa in filteredQAs" :key="qa.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                             <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', 
                                qa.category === 'tour' ? 'bg-indigo-100 text-indigo-800' : 
                                qa.category === 'support' ? 'bg-green-100 text-green-800' : 
                                'bg-gray-100 text-gray-800']">
                                {{ qa.category.charAt(0).toUpperCase() + qa.category.slice(1) }}
                             </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 line-clamp-2" :title="qa.question">{{ qa.question }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 line-clamp-2" :title="qa.answer">{{ qa.answer }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', qa.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                                {{ qa.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                             <button @click="openModal(qa)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                             <button @click="deleteQA(qa.id)" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
         <div v-if="filteredQAs.length === 0" class="text-center py-12 text-gray-500">
             No Q&A found.
         </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed z-[9999] inset-0 overflow-y-auto " aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
             <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>
             <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
             
             <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative z-[10000]">
                <form @submit.prevent="saveQA">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ form.id ? 'Edit Q&A' : 'Add New Q&A' }}
                        </h3>
                        <div class="mt-4 space-y-4">
                             <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select v-model="form.category" id="category" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                                    <option value="tour">Tour</option>
                                    <option value="support">Support</option>
                                    <option value="general">General</option>
                                </select>
                            </div>
                            <div>
                                <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                                <input v-model="form.question" type="text" id="question" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                            </div>
                             <div>
                                <label for="answer" class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                                <textarea v-model="form.answer" id="answer" rows="3" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>
                             <div class="flex items-center">
                                <input v-model="form.is_active" id="is_active" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900"> Active </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
             </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import api from '../../services/api'

const qas = ref([])
const filterCategory = ref('all')
const showModal = ref(false)
const form = reactive({
    id: null,
    question: '',
    answer: '',
    category: 'general',
    is_active: true
})

const fetchQAs = async () => {
    try {
        const response = await api.get('/chatbot')
        qas.value = response.data
    } catch (error) {
        console.error("Failed to fetch Q&As", error)
    }
}

const filteredQAs = computed(() => {
    if (filterCategory.value === 'all') return qas.value
    return qas.value.filter(qa => qa.category === filterCategory.value)
})

const openModal = (qa = null) => {
    if (qa) {
        form.id = qa.id
        form.question = qa.question
        form.answer = qa.answer
        form.category = qa.category
        form.is_active = qa.is_active
    } else {
        form.id = null
        form.question = ''
        form.answer = ''
        form.category = 'general'
        form.is_active = true
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}

const saveQA = async () => {
    try {
        if (form.id) {
            await api.put(`/admin/chatbot/${form.id}`, form)
        } else {
            await api.post('/admin/chatbot', form)
        }
        closeModal()
        fetchQAs()
    } catch (error) {
        console.error("Failed to save Q&A", error)
        alert("Failed to save. Please try again.")
    }
}

const deleteQA = async (id) => {
    if (!confirm("Are you sure you want to delete this Q&A?")) return

    try {
        await api.delete(`/admin/chatbot/${id}`)
        fetchQAs()
    } catch (error) {
        console.error("Failed to delete Q&A", error)
        alert("Failed to delete.")
    }
}

onMounted(() => {
    fetchQAs()
})
</script>
