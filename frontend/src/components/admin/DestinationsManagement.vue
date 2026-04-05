<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Destinations Management</h2>
            <button @click="openModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Destination
            </button>
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
            <ul class="divide-y divide-gray-200">
                <li v-for="dest in destinations" :key="dest.id" class="hover:bg-gray-50 transition-colors">
                    <div class="px-6 py-4 flex items-center">
                        <div class="min-w-0 flex-1 flex items-center">
                            <img class="h-12 w-12 rounded-lg object-cover mr-4 shadow-sm" :src="dest.image_url || 'https://via.placeholder.com/100'" alt="">
                            <div>
                                <p class="font-medium text-indigo-600 truncate text-lg">{{ dest.name }}</p>
                                <p class="text-sm text-gray-500 mt-1 truncate max-w-md">{{ dest.description }}</p>
                            </div>
                        </div>
                        <div class="ml-5 flex-shrink-0 flex items-center space-x-3">
                            <span v-if="dest.featured" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Featured
                            </span>
                            <button @click="openModal(dest)" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button @click="deleteDestination(dest.id)" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
             <div v-if="destinations.length === 0" class="text-center py-12 text-gray-500">
               No destinations found.
            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <div v-if="showModal" class="fixed z-[9999] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="closeModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[10000]">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-2xl font-serif font-bold text-gray-900 mb-6" id="modal-title">
                                {{ isEditing ? 'Edit Destination' : 'Add New Destination' }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input v-model="form.name" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                                    <input v-model="form.slug" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 bg-gray-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <RichTextEditor v-model="form.description" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Destination Image</label>
                                    
                                    <!-- Image Preview -->
                                    <div v-if="form.image_url" class="mb-4 relative group aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden w-full h-48">
                                        <img :src="form.image_url" class="object-cover w-full h-full" alt="Destination Image">
                                        <button @click.prevent="removeImage" class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <!-- Upload Area -->
                                    <div v-else class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors relative">
                                        <div class="space-y-1 text-center">
                                            <div v-if="isUploading" class="flex flex-col items-center">
                                                 <svg class="animate-spin h-8 w-8 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <p class="text-sm text-gray-500">Uploading...</p>
                                            </div>
                                            <div v-else>
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                        <span>Upload a file</span>
                                                        <input id="file-upload" name="file-upload" type="file" class="sr-only" @change="handleFileUpload" accept="image/*">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Manual URL Entry (Optional/Hidden) -->
                                    <div class="mt-2 text-right">
                                         <details class="text-xs text-gray-500 cursor-pointer inline-block">
                                            <summary>Enter URL manually</summary>
                                             <input v-model="form.image_url" type="text" placeholder="https://..." class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs p-2 bg-gray-50 text-left">
                                         </details>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <input v-model="form.featured" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900">Featured Destination</label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                            <button @click="saveDestination" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ isEditing ? 'Update' : 'Create' }}
                            </button>
                            <button @click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
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

const destinations = ref([])
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)

const form = ref({
    name: '',
    slug: '',
    description: '',
    image_url: '',
    featured: false
})

const fetchDestinations = async () => {
    try {
        const response = await api.get('/destinations')
        destinations.value = response.data
    } catch (error) {
        console.error("Failed to fetch destinations", error)
    }
}

const openModal = (dest = null) => {
    if (dest) {
        isEditing.value = true
        editingId.value = dest.id
        form.value = {
            name: dest.name,
            slug: dest.slug,
            description: dest.description,
            image_url: dest.image_url,
            featured: !!dest.featured
        }
    } else {
        isEditing.value = false
        editingId.value = null
        form.value = {
            name: '',
            slug: '',
            description: '',
            image_url: '',
            featured: false
        }
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    form.value = { name: '', slug: '', description: '', image_url: '', featured: false }
}

const saveDestination = async () => {
    try {
        const payload = {
            ...form.value,
            featured: form.value.featured ? 1 : 0
        }
        
        if (isEditing.value) {
            await api.put(`/destinations/${editingId.value}`, payload)
        } else {
            await api.post('/destinations', payload)
        }
        
        closeModal()
        fetchDestinations()
    } catch (error) {
        console.error("Failed to save destination", error)
        alert("Failed to save destination")
    }
}

const deleteDestination = async (id) => {
    if(confirm("Are you sure you want to delete this destination?")) {
        try {
            await api.delete(`/destinations/${id}`)
            fetchDestinations()
        } catch (error) {
            console.error("Failed to delete destination", error)
            alert("Failed to delete destination")
        }
    }
}



const isUploading = ref(false)

const handleFileUpload = async (event) => {
    const file = event.target.files[0]
    if (!file) return

    const formData = new FormData()
    formData.append('file', file)

    isUploading.value = true

    try {
        const response = await api.post('/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        if (response.data.url) {
            form.value.image_url = response.data.url
        }
    } catch (error) {
        console.error("Upload failed", error)
        alert("Found error during upload: " + (error.response?.data?.message || error.message))
    } finally {
        isUploading.value = false
        // Reset file input
        event.target.value = ''
    }
}

const removeImage = () => {
    form.value.image_url = ''
}

onMounted(() => {
    fetchDestinations()
})
</script>
