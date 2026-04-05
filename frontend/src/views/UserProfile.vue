<template>
  <div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your personal information and profile details.</p>
      </div>

      <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        
        <!-- Sidebar / Navigation (Desktop) -->
        <aside class="py-6 lg:col-span-3">
          <nav class="space-y-1">
            <button @click="activeTab = 'profile'" :class="[activeTab === 'profile' ? 'bg-gray-100 text-brand-secondary border-brand-secondary' : 'text-gray-900 hover:bg-gray-50 hover:text-brand-secondary border-transparent', 'group rounded-md px-3 py-2 flex items-center text-sm font-medium border-l-4 w-full']">
              <svg :class="[activeTab === 'profile' ? 'text-brand-secondary' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-6 w-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
              <span class="truncate">Profile</span>
            </button>
            <button @click="activeTab = 'bookings'" :class="[activeTab === 'bookings' ? 'bg-gray-100 text-brand-secondary border-brand-secondary' : 'text-gray-900 hover:bg-gray-50 hover:text-brand-secondary border-transparent', 'group rounded-md px-3 py-2 flex items-center text-sm font-medium border-l-4 w-full']">
               <svg :class="[activeTab === 'bookings' ? 'text-brand-secondary' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-6 w-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
               <span class="truncate">Booking History</span>
            </button>
          </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="lg:col-span-9">
            
            <!-- Profile Tab -->
            <form v-if="activeTab === 'profile'" @submit.prevent="saveProfile" class="shadow-sm rounded-lg bg-white overflow-hidden border border-gray-200">
                
                <!-- Profile Header / Avatar -->
                <div class="px-6 py-8 border-b border-gray-200 flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                    <div class="relative group">
                         <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md bg-gray-100 ring-4 ring-gray-50">
                            <img v-if="form.profile_image" :src="form.profile_image" alt="Profile" class="w-full h-full object-cover">
                             <div v-else class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                             </div>
                         </div>
                         <button type="button" @click="triggerFileInput" class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-lg border border-gray-200 hover:bg-gray-50 focus:outline-none transition-transform transform hover:scale-105 active:scale-95">
                             <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                         </button>
                         <input type="file" ref="fileInput" class="hidden" @change="handleImageUpload" accept="image/*">
                         <div v-if="isUploading" class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center">
                             <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                         </div>
                    </div>
                    
                    <div class="text-center sm:text-left">
                        <h2 class="text-2xl font-bold text-gray-900">{{ form.first_name }} {{ form.last_name }}</h2>
                        <div class="flex items-center justify-center sm:justify-start space-x-2 mt-1">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                {{ authStore.user?.role || 'User' }}
                             </span>
                             <span class="text-sm text-gray-500">{{ form.email }}</span>
                        </div>
                        <p class="mt-4 text-sm text-gray-500 max-w-sm">
                            {{ form.bio || 'No bio added yet. Tell us a bit about yourself!' }}
                        </p>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-8">
                    
                    <!-- Personal Info Section -->
                    <section>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 border-b border-gray-100 pb-2 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            
                            <div class="sm:col-span-3">
                                <label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
                                <div class="mt-1">
                                    <input type="text" id="first-name" v-model="form.first_name" class="shadow-sm focus:ring-brand-secondary focus:border-brand-secondary block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 transition-colors" placeholder="e.g. Aditi">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                                <div class="mt-1">
                                     <input type="text" id="last-name" v-model="form.last_name" class="shadow-sm focus:ring-brand-secondary focus:border-brand-secondary block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 transition-colors" placeholder="e.g. Sharma">
                                </div>
                            </div>
                             
                             <div class="sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                    </div>
                                    <input type="email" id="email" v-model="form.email" class="focus:ring-brand-secondary focus:border-brand-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-md bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">To change your email, please contact support.</p>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <input type="tel" id="phone" v-model="form.phone" class="focus:ring-brand-secondary focus:border-brand-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 px-3">
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="about" class="block text-sm font-medium text-gray-700">About</label>
                                <div class="mt-1">
                                    <textarea id="about" v-model="form.bio" rows="4" class="shadow-sm focus:ring-brand-secondary focus:border-brand-secondary block w-full sm:text-sm border border-gray-300 rounded-md p-3" placeholder="Tell us about yourself..."></textarea>
                                </div>
                                <div class="flex justify-between mt-2">
                                     <p class="text-xs text-gray-500">Brief description for your profile.</p>
                                      <p :class="`text-xs ${bioWordCount > 200 || (bioWordCount > 0 && bioWordCount < 5) ? 'text-red-600' : 'text-gray-500'}`">
                                        {{ bioWordCount }} words (Max 200)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Social Links Section -->
                    <section>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 border-b border-gray-100 pb-2 mb-4">Social Presence</h3>
                        <div class="space-y-4">
                             <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm font-medium">
                                    Facebook
                                </span>
                                <input type="url" v-model="form.social_links.facebook" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-brand-secondary focus:border-brand-secondary sm:text-sm border-gray-300" placeholder="https://facebook.com/username">
                            </div>
                            
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm font-medium">
                                    Instagram
                                </span>
                                <input type="url" v-model="form.social_links.instagram" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-brand-secondary focus:border-brand-secondary sm:text-sm border-gray-300" placeholder="https://instagram.com/username">
                            </div>

                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm font-medium">
                                    Twitter / X
                                </span>
                                <input type="url" v-model="form.social_links.twitter" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-brand-secondary focus:border-brand-secondary sm:text-sm border-gray-300" placeholder="https://twitter.com/username">
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                     <p v-if="message" :class="`text-sm font-medium ${messageType === 'success' ? 'text-green-600' : 'text-red-600'}`">
                        {{ message }}
                    </p>
                    <div v-else class="text-sm text-gray-500 italic">
                         Last saved: never <!-- Could implement timestamp -->
                    </div>

                    <button type="submit" :disabled="isLoading || hasErrors" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-brand-secondary hover:bg-brand-secondary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ isLoading ? 'Saving Changes...' : 'Save Changes' }}
                    </button>
                </div>
            </form>

            <!-- Booking History Tab -->
            <div v-if="activeTab === 'bookings'" class="shadow-sm rounded-lg bg-white overflow-hidden border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">My Bookings</h3>
                    <p class="mt-1 text-sm text-gray-500">History of your tours and travels.</p>
                </div>
                
                <div class="md:p-6 p-4">
                     <div v-if="loadingBookings" class="flex justify-center py-10">
                         <svg class="animate-spin h-8 w-8 text-brand-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                           <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                           <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                         </svg>
                     </div>

                     <div v-else-if="bookings.length === 0" class="text-center py-10">
                         <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                          </svg>
                          <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                          <p class="mt-1 text-sm text-gray-500">You haven't booked any tours yet.</p>
                          <div class="mt-6">
                            <router-link to="/tours" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-secondary hover:bg-brand-secondary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary">
                              Explore Tours
                            </router-link>
                          </div>
                     </div>

                     <div v-else class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                              <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                  <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                      Tour
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                      Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                      Total
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                      Date
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                      <span class="sr-only">View</span>
                                    </th>
                                  </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                  <tr v-for="booking in bookings" :key="booking.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                      <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                          <img v-if="booking.image" class="h-10 w-10 rounded-full object-cover" :src="booking.image" alt="">
                                          <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                              <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                          </div>
                                        </div>
                                        <div class="ml-4">
                                          <div class="text-sm font-medium text-gray-900">
                                            {{ booking.title }}
                                          </div>
                                          <div class="text-sm text-gray-500">
                                            {{ booking.location }}
                                          </div>
                                        </div>
                                      </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                      <span :class="[
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        booking.status === 'Confirmed' ? 'bg-green-100 text-green-800' : 
                                        booking.status === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                                        booking.status === 'Completed' ? 'bg-blue-100 text-blue-800' :
                                        'bg-red-100 text-red-800'
                                      ]">
                                        {{ booking.status }}
                                      </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                      ${{ booking.total }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                      <div v-if="booking.start_date">
                                          {{ new Date(booking.start_date).toLocaleDateString() }} - {{ new Date(booking.end_date).toLocaleDateString() }}
                                      </div>
                                      <div v-else>
                                          {{ new Date(booking.booking_date).toLocaleDateString() }}
                                      </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                      <!-- Placeholder for now -->
                                     <!-- <a href="#" class="text-brand-secondary hover:text-orange-900">View</a> -->
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                     </div>
                </div>
            </div>

        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue'
import { useAuthStore } from '../stores/auth'
import api from '../services/api'

const authStore = useAuthStore()
const fileInput = ref(null)
const isUploading = ref(false)
const isLoading = ref(false)
const message = ref('')
const messageType = ref('')
const activeTab = ref('profile')
const bookings = ref([])
const loadingBookings = ref(false)

const form = reactive({
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    phone: '',
    profile_image: '',
    bio: '',
    social_links: {
        facebook: '',
        instagram: '',
        twitter: ''
    }
})

const triggerFileInput = () => {
    fileInput.value.click()
}

const handleImageUpload = async (event) => {
    const file = event.target.files[0]
    if (!file) return

    // Preview
    const reader = new FileReader()
    reader.onload = (e) => {
        form.profile_image = e.target.result // Temporary preview
    }
    reader.readAsDataURL(file)

    const formData = new FormData()
    formData.append('file', file)

    isUploading.value = true
    try {
        const response = await api.post('/upload', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        form.profile_image = response.data.url
        showMessage('Profile picture uploaded!', 'success')
    } catch (error) {
        console.error("Upload failed", error)
        showMessage('Image upload failed. Please try again.', 'error')
    } finally {
        isUploading.value = false
    }
}

const bioWordCount = computed(() => {
    if (!form.bio) return 0
    return form.bio.trim().split(/\s+/).filter(w => w.length > 0).length
})

const hasErrors = computed(() => {
    if (bioWordCount.value > 200) return true
    return false
})

const loadProfile = async () => {
    try {
        const data = await authStore.fetchProfile()
        if (data) {
            form.first_name = data.first_name || ''
            form.middle_name = data.middle_name || ''
            form.last_name = data.last_name || ''
            form.email = data.email || ''
            form.phone = data.phone || ''
            form.profile_image = data.profile_image || ''
            form.bio = data.bio || ''
            
            if (data.social_links) {
                const links = typeof data.social_links === 'string' ? JSON.parse(data.social_links) : data.social_links
                form.social_links = { ...form.social_links, ...links }
            }
        }
    } catch (error) {
        console.error("Failed to load profile", error)
        showMessage('Could not load profile data.', 'error')
    }
}

const fetchBookings = async () => {
    loadingBookings.value = true
    try {
        const response = await api.get('/user/bookings')
        bookings.value = response.data.data
    } catch (error) {
        console.error("Error fetching bookings:", error)
    } finally {
        loadingBookings.value = false
    }
}

const saveProfile = async () => {
    if (hasErrors.value) return

    isLoading.value = true
    message.value = ''
    try {
        const payload = {
            ...form,
            social_links: { ...form.social_links }
        }
        
        await authStore.updateProfile(payload)
        showMessage('Profile updated successfully!', 'success')
        await authStore.fetchProfile() 
        
    } catch (error) {
        console.error("Failed to update profile", error)
        const errorMsg = error.response?.data?.message || 'Failed to update profile. Please try again.'
        showMessage(errorMsg, 'error')
    } finally {
        isLoading.value = false
    }
}

const showMessage = (msg, type) => {
    message.value = msg
    messageType.value = type
    if (type === 'success') {
        setTimeout(() => {
            message.value = ''
        }, 4000)
    }
}

// Watch activeTab to load bookings when switched
watch(activeTab, (newTab) => {
    if (newTab === 'bookings' && bookings.value.length === 0) {
        fetchBookings()
    }
})

onMounted(() => {
    loadProfile()
})
</script>
