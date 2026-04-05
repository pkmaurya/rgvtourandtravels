<template>
  <div class="relative h-[85vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
      <img src="https://images.unsplash.com/photo-1598091383021-15ddea10925d?q=80&w=2000&auto=format&fit=crop" 
           alt="Incredible India Landscape" 
           class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/60 to-black/70"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
      <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 animate-fade-in-up">
        Discover the Soul of <span class="text-brand-secondary font-serif italic">Incredible India</span>
      </h1>
      <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-3xl mx-auto leading-relaxed animate-fade-in-up delay-100">
        Experience breathtaking landscapes, timeless heritage, and vibrant culture. 
        Your journey to the extraordinary begins here.
      </p>

      <!-- Search/Filter Bar -->
      <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-2xl border border-white/20 animate-fade-in-up delay-200">
        <form @submit.prevent="handleSearch" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            
          <!-- Tour Package Select -->
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-300 group-focus-within:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <select 
              v-model="searchQuery" 
              class="block w-full pl-10 pr-3 py-3 border border-transparent rounded-xl leading-5 bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:bg-white focus:text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-transparent sm:text-sm appearance-none transition-all duration-300"
            >
                <option value="" class="text-gray-900">Select Tour Package</option>
                <option v-for="tour in tours" :key="tour.id" :value="tour.title" class="text-gray-900">
                    {{ tour.title }}
                </option>
            </select>
          </div>

          <!-- Duration Input -->
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-300 group-focus-within:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
             <select 
              v-model="duration" 
              class="block w-full pl-10 pr-3 py-3 border border-transparent rounded-xl leading-5 bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:bg-white focus:text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-transparent sm:text-sm appearance-none transition-all duration-300"
             >
                <option value="" class="text-gray-900">Duration (Any)</option>
                <option value="3-5" class="text-gray-900">3-5 Days</option>
                <option value="6-10" class="text-gray-900">6-10 Days</option>
                <option value="10+" class="text-gray-900">10+ Days</option>
             </select>
          </div>

          <!-- Date Start -->
          <div class="relative group">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
               <svg class="h-5 w-5 text-gray-300 group-focus-within:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
               </svg>
             </div>
             <input 
               v-model="dateStart"
               type="text"
               onfocus="(this.type='date')"
               onblur="(this.type='text')"
               placeholder="Start Date"
               class="block w-full pl-10 pr-3 py-3 border border-transparent rounded-xl leading-5 bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:bg-white focus:text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-transparent sm:text-sm transition-all duration-300"
             />
          </div>

          <!-- Date End -->
          <div class="relative group">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
               <svg class="h-5 w-5 text-gray-300 group-focus-within:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
               </svg>
             </div>
             <input 
               v-model="dateEnd"
               type="text"
               onfocus="(this.type='date')"
               onblur="(this.type='text')"
               placeholder="End Date"
               class="block w-full pl-10 pr-3 py-3 border border-transparent rounded-xl leading-5 bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:bg-white focus:text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-transparent sm:text-sm transition-all duration-300"
             />
          </div>

          <!-- Time Slot -->
           <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-300 group-focus-within:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
             <select 
              v-model="timeSlot" 
              class="block w-full pl-10 pr-3 py-3 border border-transparent rounded-xl leading-5 bg-white/20 text-white placeholder-gray-300 focus:outline-none focus:bg-white focus:text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-transparent sm:text-sm appearance-none transition-all duration-300"
             >
                <option value="" class="text-gray-900">Time Slot (Any)</option>
                <option value="morning" class="text-gray-900">Morning</option>
                <option value="afternoon" class="text-gray-900">Afternoon</option>
                <option value="evening" class="text-gray-900">Evening</option>
             </select>
          </div>

          <button 
            type="submit" 
            class="w-full bg-gradient-to-r from-brand-secondary to-brand-accent hover:from-brand-accent hover:to-brand-secondary text-white font-bold py-3 px-8 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary"
          >
            Search
          </button>
        </form>
      </div>
    </div>
    
    <!-- Scroll Down Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
       <svg class="w-6 h-6 text-white opacity-75" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
         <path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
       </svg>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';

const router = useRouter();
const searchQuery = ref('');
const duration = ref('');
const dateStart = ref('');
const dateEnd = ref('');
const timeSlot = ref('');
const tours = ref([]);

const fetchTours = async () => {
    try {
        const response = await api.get('/tours?limit=100'); // Fetch enough tours for dropdown
        if (response.data.data) {
             tours.value = response.data.data;
        } else {
             tours.value = response.data;
        }
    } catch (error) {
        console.error("Failed to fetch tours for dropdown", error);
    }
};

const handleSearch = () => {
    // Redirect to tours page with query params
    router.push({ 
        path: '/tours', 
        query: { 
            q: searchQuery.value, 
            duration: duration.value,
            start: dateStart.value,
            end: dateEnd.value,
            time: timeSlot.value
        } 
    });
};

onMounted(() => {
    fetchTours();
});
</script>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
  opacity: 0;
  transform: translateY(20px);
}

.delay-100 {
  animation-delay: 0.1s;
}

.delay-200 {
  animation-delay: 0.2s;
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
