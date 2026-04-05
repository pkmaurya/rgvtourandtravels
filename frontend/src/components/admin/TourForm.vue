<template>
  <div class="fixed z-[9999] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="$emit('close')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative z-[10000]">
            
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ isEditing ? 'Edit Tour' : 'Add New Tour' }}
                </h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Tabs -->
            <div class="bg-white border-b border-gray-200">
                <nav class="-mb-px flex px-6" aria-label="Tabs">
                    <button v-for="tab in tabs" :key="tab.id" @click="currentTab = tab.id"
                        :class="[currentTab === tab.id ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8']">
                        {{ tab.name }}
                    </button>
                </nav>
            </div>

            <!-- Methods Body -->
            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto bg-gray-50">
                
                <!-- Tab: Basic Info -->
                <div v-if="currentTab === 'basic'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                             <label class="block text-sm font-medium text-gray-700 mb-1">Tour Title</label>
                             <input v-model="form.title" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Slug (Link)</label>
                             <input v-model="form.slug" type="text" placeholder="Auto-generated if empty" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                             <select v-model="form.destination_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                                 <option :value="null">Select Destination</option>
                                 <option v-for="dest in destinations" :key="dest.id" :value="dest.id">{{ dest.name }}</option>
                             </select>
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                             <select v-model="form.category" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                                 <option value="General">General</option>
                                 <option value="Adventure">Adventure</option>
                                 <option value="Honeymoon">Honeymoon</option>
                                 <option value="Family">Family</option>
                                 <option value="Luxury">Luxury</option>
                                 <option value="Spiritual">Spiritual</option>
                             </select>
                        </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                             <select v-model="form.status" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                                 <option value="active">Active</option>
                                 <option value="inactive">Inactive</option>
                                 <option value="sold_out">Sold Out</option>
                             </select>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input v-model="form.featured" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Mark as Featured Tour</label>
                    </div>
                </div>

                <!-- Tab: Pricing & Dates -->
                <div v-if="currentTab === 'pricing'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (₹)</label>
                             <input v-model="form.price" type="number" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                        </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price (Optional)</label>
                             <input v-model="form.sale_price" type="number" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                        </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                             <input v-model="form.duration" type="text" placeholder="e.g. 5 Days / 4 Nights" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                        </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Max Travelers</label>
                             <input v-model="form.max_travelers" type="number" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Dates (Optional)</label>
                        <p class="text-xs text-gray-500 mb-2">Leave empty for "Available Daily"</p>
                         <div class="flex gap-2 mb-2">
                             <input v-model="newDate" type="date" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                             <button @click="addDate" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 rounded-lg">Add</button>
                         </div>
                         <div class="flex flex-wrap gap-2">
                             <span v-for="(date, idx) in form.available_dates" :key="idx" class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs flex items-center">
                                 {{ date }}
                                 <button @click="removeDate(idx)" class="ml-1 text-indigo-500 hover:text-indigo-900">&times;</button>
                             </span>
                         </div>
                    </div>
                </div>

                <!-- Tab: Details -->
                <div v-if="currentTab === 'details'" class="space-y-6">
                    <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                         <RichTextEditor v-model="form.description" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Highlights</label>
                        <div class="flex gap-2 mb-2">
                             <input v-model="newHighlight" type="text" placeholder="Add a highlight..." @keyup.enter="addHighlight" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                             <button @click="addHighlight" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 rounded-lg">Add</button>
                         </div>
                          <ul class="space-y-1">
                             <li v-for="(hl, idx) in form.highlights" :key="idx" class="flex justify-between items-center bg-white p-2 border rounded-lg text-sm">
                                 <span>{{ hl }}</span>
                                 <button @click="removeHighlight(idx)" class="text-red-500 hover:text-red-700">&times;</button>
                             </li>
                         </ul>
                    </div>
                     <div class="grid grid-cols-2 gap-6">
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Inclusions</label>
                            <div class="flex gap-2 mb-2">
                                <input v-model="newInclusion" type="text" placeholder="Add inclusion..." @keyup.enter="addInclusion" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                                <button @click="addInclusion" class="bg-green-100 hover:bg-green-200 text-green-700 px-3 rounded-lg">+</button>
                            </div>
                            <ul class="space-y-1 max-h-40 overflow-y-auto">
                                <li v-for="(inc, idx) in form.inclusions" :key="idx" class="flex justify-between items-center bg-white p-2 border rounded-lg text-sm">
                                    <span>{{ inc }}</span>
                                    <button @click="removeInclusion(idx)" class="text-gray-400 hover:text-red-500">&times;</button>
                                </li>
                            </ul>
                         </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Exclusions</label>
                            <div class="flex gap-2 mb-2">
                                <input v-model="newExclusion" type="text" placeholder="Add exclusion..." @keyup.enter="addExclusion" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                                <button @click="addExclusion" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 rounded-lg">+</button>
                            </div>
                             <ul class="space-y-1 max-h-40 overflow-y-auto">
                                <li v-for="(exc, idx) in form.exclusions" :key="idx" class="flex justify-between items-center bg-white p-2 border rounded-lg text-sm">
                                    <span>{{ exc }}</span>
                                    <button @click="removeExclusion(idx)" class="text-gray-400 hover:text-red-500">&times;</button>
                                </li>
                            </ul>
                         </div>
                     </div>
                </div>

                <!-- Tab: Itinerary -->
                <div v-if="currentTab === 'itinerary'" class="space-y-6">
                     <div class="flex justify-between items-center">
                         <h4 class="font-bold text-gray-900">Day-wise Itinerary</h4>
                         <button @click="addItineraryDay" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">+ Add Day</button>
                     </div>
                     <div class="space-y-4">
                         <div v-for="(day, idx) in form.itinerary" :key="idx" class="bg-white border rounded-lg p-4 relative">
                             <div class="absolute top-2 right-2">
                                 <button @click="removeItineraryDay(idx)" class="text-red-400 hover:text-red-600">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                 </button>
                             </div>
                             <div class="grid grid-cols-1 gap-4">
                                 <div>
                                     <label class="block text-xs font-medium text-gray-500 mb-1">Day {{ idx + 1 }} Title</label>
                                     <input v-model="day.title" type="text" placeholder="e.g. Arrival in Delhi" class="block w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                                 </div>
                                 <div>
                                      <label class="block text-xs font-medium text-gray-500 mb-1">Details</label>
                                      <RichTextEditor v-model="day.details" placeholder="Describe the day's activities..." />
                                 </div>
                             </div>
                         </div>
                     </div>
                </div>

                <!-- Tab: Media -->
                <div v-if="currentTab === 'media'" class="space-y-6">
                    <div class="space-y-4">
                         <label class="block text-sm font-medium text-gray-700">Tour Images</label>
                         
                         <!-- Upload/Drag Area -->
                         <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors relative">
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

                        <!-- Gallery -->
                        <div v-if="form.images && form.images.length" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                             <div v-for="(img,idx) in form.images" :key="idx" class="relative group aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden border">
                                <img :src="img" class="object-cover w-full h-full">
                                <button @click="removeImage(idx)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <div class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-0.5 rounded" v-if="idx===0">Cover</div>
                             </div>
                        </div>
                    </div>
                </div>

            </div>

             <!-- Footer -->
             <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-row-reverse gap-3">
                 <button @click="save" class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                     {{ isEditing ? 'Update Tour' : 'Create Tour' }}
                 </button>
                 <button @click="$emit('close')" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                     Cancel
                 </button>
             </div>

        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '../../services/api'
import RichTextEditor from '../RichTextEditor.vue'

const props = defineProps({
    tour: {
        type: Object,
        default: null
    },
    destinations: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['close', 'saved'])

const currentTab = ref('basic')
const tabs = [
    { id: 'basic', name: 'Basic Info' },
    { id: 'pricing', name: 'Pricing & Dates' },
    { id: 'details', name: 'Details' },
    { id: 'itinerary', name: 'Itinerary' },
    { id: 'media', name: 'Media' }
]

const isEditing = ref(false)
const isUploading = ref(false)

const form = reactive({
    id: null,
    title: '',
    slug: '',
    destination_id: null,
    category: 'General',
    status: 'active',
    featured: false,
    price: '',
    sale_price: '',
    duration: '',
    max_travelers: 12,
    available_dates: [],
    description: '',
    highlights: [],
    inclusions: [],
    exclusions: [],
    itinerary: [],
    images: [] // Array of URLs
})

// Helpers for array fields
const newDate = ref('')
const newHighlight = ref('')
const newInclusion = ref('')
const newExclusion = ref('')

const addDate = () => { if(newDate.value) { form.available_dates.push(newDate.value); newDate.value = '' } }
const removeDate = (idx) => form.available_dates.splice(idx, 1)

const addHighlight = () => { if(newHighlight.value) { form.highlights.push(newHighlight.value); newHighlight.value = '' } }
const removeHighlight = (idx) => form.highlights.splice(idx, 1)

const addInclusion = () => { if(newInclusion.value) { form.inclusions.push(newInclusion.value); newInclusion.value = '' } }
const removeInclusion = (idx) => form.inclusions.splice(idx, 1)

const addExclusion = () => { if(newExclusion.value) { form.exclusions.push(newExclusion.value); newExclusion.value = '' } }
const removeExclusion = (idx) => form.exclusions.splice(idx, 1)

const addItineraryDay = () => form.itinerary.push({ title: '', details: '' })
const removeItineraryDay = (idx) => form.itinerary.splice(idx, 1)

const handleFileUpload = async (event) => {
    const file = event.target.files[0]
    if (!file) return
    isUploading.value = true
    const formData = new FormData()
    formData.append('file', file)
    try {
        const response = await api.post('/upload', formData, {headers: {'Content-Type': 'multipart/form-data'}})
        if (response.data.url) form.images.push(response.data.url)
    } catch (e) {
        alert("Upload failed")
    } finally {
        isUploading.value = false
        event.target.value = ''
    }
}
const removeImage = (idx) => form.images.splice(idx, 1)

const save = async () => {
    try {
        const payload = { ...form }
        console.log("Saving Tour Payload:", payload);
        // Ensure numbers
        if(form.id) {
            await api.put(`/tours/${form.id}`, payload)
        } else {
            await api.post('/tours', payload)
        }
        emit('saved')
    } catch (error) {
        console.error("Save failed", error)
        alert("Failed to save tour. Please check fields.")
    }
}

onMounted(() => {
    if (props.tour) {
        isEditing.value = true
        // Deep copy tour data
        const tourData = JSON.parse(JSON.stringify(props.tour))
        Object.assign(form, tourData) 
        
        // Ensure destination_id is reactive and correctly set
        // Sometimes backend sends it as string or object, force it to be ID
        if(tourData.destination_id) form.destination_id = tourData.destination_id
        
        // Ensure arrays are initialized if null
        if (!form.available_dates) form.available_dates = []
        if (!form.highlights) form.highlights = []
        if (!form.inclusions) form.inclusions = []
        if (!form.exclusions) form.exclusions = []
        if (!form.itinerary) form.itinerary = []
        if (!form.images) form.images = []
    }
})
</script>
