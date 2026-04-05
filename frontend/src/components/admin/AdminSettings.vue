<template>
  <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-100">
     <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
         <h2 class="text-lg font-bold text-gray-900">Site Settings</h2>
         <button @click="saveSettings" :disabled="loading" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 disabled:opacity-50">
             <svg v-if="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
             {{ loading ? 'Saving...' : 'Save Changes' }}
         </button>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex px-6" aria-label="Tabs">
            <button v-for="tab in tabs" :key="tab.id" @click="currentTab = tab.id"
                :class="[currentTab === tab.id ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8']">
                {{ tab.name }}
            </button>
        </nav>
    </div>

    <!-- Content -->
    <div class="p-6">
        <!-- General Settings -->
        <div v-if="currentTab === 'general'" class="space-y-6 max-w-2xl">
            <div>
                 <label class="block text-sm font-medium text-gray-700 mb-1">Site Title</label>
                 <input v-model="settings.site_title" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
            </div>
            <div>
                 <label class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                 <RichTextEditor v-model="settings.site_description" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Site Logo</label>
                     <div class="flex items-center gap-4">
                        <img v-if="settings.site_logo" :src="settings.site_logo" class="h-12 w-auto object-contain border rounded p-1">
                        <input @change="handleFileUpload($event, 'site_logo')" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                     </div>
                </div>
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                     <div class="flex items-center gap-4">
                        <img v-if="settings.site_favicon" :src="settings.site_favicon" class="h-8 w-8 object-contain border rounded p-1">
                        <input @change="handleFileUpload($event, 'site_favicon')" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                     </div>
                </div>
            </div>

             <div class="grid grid-cols-2 gap-6">
                 <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                     <input v-model="settings.contact_email" type="email" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                 </div>
                 <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                     <input v-model="settings.contact_phone" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                 </div>
             </div>
        </div>

        <!-- Payment Settings -->
        <div v-if="currentTab === 'payment'" class="space-y-6 max-w-2xl">
             <div>
                 <label class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                 <input v-model="settings.currency_symbol" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
            </div>
            
            <div class="border-t pt-4">
                <h4 class="text-sm font-bold text-gray-900 mb-3">Razorpay</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Key ID</label>
                        <input v-model="settings.razorpay_key_id" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Key Secret</label>
                        <input v-model="settings.razorpay_key_secret" type="password" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                    </div>
                </div>
            </div>

            <div class="border-t pt-4">
                <h4 class="text-sm font-bold text-gray-900 mb-3">Stripe</h4>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Publishable Key</label>
                        <input v-model="settings.stripe_publishable_key" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                        <input v-model="settings.stripe_secret_key" type="password" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div v-if="currentTab === 'email'" class="space-y-6 max-w-2xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                     <input v-model="settings.smtp_host" type="text" placeholder="smtp.gmail.com" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                 </div>
                 <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                     <input v-model="settings.smtp_port" type="text" placeholder="587" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                 </div>
                 <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">SMTP User</label>
                     <input v-model="settings.smtp_user" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                 </div>
                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Password</label>
                     <input v-model="settings.smtp_pass" type="password" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5">
                 </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import RichTextEditor from '../RichTextEditor.vue'

const loading = ref(false)
const currentTab = ref('general')

const tabs = [
    { id: 'general', name: 'General' },
    { id: 'payment', name: 'Payment Gateways' },
    { id: 'email', name: 'Email Configuration' },
]

const settings = ref({
    site_title: '',
    site_description: '',
    site_logo: '',
    site_favicon: '',
    contact_email: '',
    contact_phone: '',
    currency_symbol: '₹',
    razorpay_key_id: '',
    razorpay_key_secret: '',
    stripe_publishable_key: '',
    stripe_secret_key: '',
    smtp_host: '',
    smtp_port: '',
    smtp_user: '',
    smtp_pass: ''
})

const handleFileUpload = async (event, key) => {
    const file = event.target.files[0]
    if (!file) return

    const formData = new FormData()
    formData.append('file', file)

    try {
        const response = await api.post('/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        if (response.data.url) {
            settings.value[key] = response.data.url
        }
    } catch (error) {
        console.error("Upload failed", error)
        alert("Failed to upload image")
    }
}


const fetchSettings = async () => {
    try {
        const response = await api.get('/settings')
        if(response.data) {
            // Merge response data into settings, identifying correct keys
            Object.keys(response.data).forEach(key => {
                if(settings.value.hasOwnProperty(key)) {
                    settings.value[key] = response.data[key]
                }
            })
        }
    } catch (error) {
        console.error("Failed to fetch settings", error)
    }
}

const saveSettings = async () => {
    loading.value = true
    try {
        // Group settings if backend expects grouping, but our backend currently accepts flat batch
        await api.put('/settings', settings.value)
        alert('Settings saved successfully!')
    } catch (error) {
         console.error("Failed to save settings", error)
         alert('Failed to save settings.')
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchSettings()
})
</script>
