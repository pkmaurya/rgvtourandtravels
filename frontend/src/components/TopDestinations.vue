<template>
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-end mb-8">
        <div>
           <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Top Destinations</h2>
           <p class="mt-2 text-gray-600">Explore our most popular locations</p>
        </div>
        <div class="hidden md:flex gap-2">
           <button @click="scrollLeft" class="p-2 rounded-full bg-white shadow hover:bg-gray-100 transition">
             <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
           </button>
           <button @click="scrollRight" class="p-2 rounded-full bg-white shadow hover:bg-gray-100 transition">
             <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
           </button>
        </div>
      </div>

      <div class="relative group">
        <!-- Scroll Container -->
        <div 
          ref="scrollContainer"
          class="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory scrollbar-hide scroll-smooth"
        >
          <div 
            v-for="destination in destinations" 
            :key="destination.id" 
            class="flex-shrink-0 w-[calc(50%-12px)] snap-start bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1"
          >
            <div class="relative h-64 overflow-hidden">
               <img 
                 :src="destination.image_url || 'https://via.placeholder.com/400'" 
                 :alt="destination.name" 
                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
               />
               <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
               <h3 class="absolute bottom-4 left-4 text-2xl font-bold text-white">{{ destination.name }}</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ destination.description }}</p>
                <router-link :to="`/destinations/${destination.slug || destination.id}`" class="inline-flex items-center text-brand-secondary font-semibold hover:text-brand-accent">
                   Explore Details
                   <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const destinations = ref([]);
const scrollContainer = ref(null);

onMounted(async () => {
    try {
        const response = await api.get('/destinations/featured');
        destinations.value = response.data;
    } catch (error) {
        console.error("Failed to load featured destinations", error);
        // Fallback data if API fails or backend not ready
        destinations.value = [
            { id: 1, name: 'Taj Mahal', image_url: 'https://images.unsplash.com/photo-1564507592333-c60657eea523', description: 'Symbol of love and one of the seven wonders.' },
            { id: 2, name: 'Varanasi', image_url: 'https://images.unsplash.com/photo-1627938823193-fd13c1c867dd', description: 'The spiritual capital of India on the banks of Ganges.' },
            { id: 3, name: 'Jaipur', image_url: 'https://images.unsplash.com/photo-1477587458883-47145ed94245', description: 'The Pink City known for its royal palaces.' },
            { id: 4, name: 'Kerala', image_url: 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944', description: 'God\'s own country with serene backwaters.' },
             { id: 5, name: 'Ladakh', image_url: 'https://images.unsplash.com/photo-1581793745862-99fde7fa73d2', description: 'Land of high passes and stunning landscapes.' },
        ];
    }
});

const scrollLeft = () => {
    scrollContainer.value.scrollBy({ left: -350, behavior: 'smooth' });
};

const scrollRight = () => {
    scrollContainer.value.scrollBy({ left: 350, behavior: 'smooth' });
};
</script>

<style scoped>
/* Scoped css for hiding scrollbar but allowing functionality */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
