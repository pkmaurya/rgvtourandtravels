<template>
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-end mb-8">
        <div>
           <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Exclusive Tour Packages</h2>
           <p class="mt-2 text-gray-600">Handpicked offers for your perfect vacation</p>
        </div>
        <div class="hidden md:flex gap-2">
           <button @click="scrollLeft" class="p-2 rounded-full bg-gray-50 shadow hover:bg-gray-100 transition">
             <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
           </button>
           <button @click="scrollRight" class="p-2 rounded-full bg-gray-50 shadow hover:bg-gray-100 transition">
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
            v-for="tour in tours" 
            :key="tour.id" 
            class="flex-shrink-0 w-80 snap-start bg-white border border-gray-100 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col"
          >
            <div class="relative h-48 overflow-hidden">
               <img 
                 :src="tour.images && tour.images[0] ? tour.images[0] : 'https://via.placeholder.com/400'" 
                 :alt="tour.title" 
                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
               />
               <span class="absolute top-4 right-4 bg-brand-accent text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                 Best Seller
               </span>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-gray-900 line-clamp-1 font-serif" :title="tour.title">{{ tour.title }}</h3>
                </div>
                <div class="flex items-center text-gray-500 text-sm mb-4">
                     <svg class="w-4 h-4 mr-1 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     {{ tour.duration }}
                </div>
                <p class="text-gray-600 text-sm line-clamp-2 mb-4 flex-1">{{ tour.description }}</p>
                
                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 block">Starting from</span>
                        <span class="text-xl font-bold text-brand-secondary">₹{{ tour.price }}</span>
                    </div>
                    <router-link :to="`/tours/${tour.slug || tour.id}`" class="px-4 py-2 bg-brand-dark text-white text-sm font-medium rounded-lg hover:bg-brand-primary transition">
                       View Deal
                    </router-link>
                </div>
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

const tours = ref([]);
const scrollContainer = ref(null);

onMounted(async () => {
    try {
        const response = await api.get('/tours/featured');
        tours.value = response.data;
    } catch (error) {
        console.error("Failed to load featured tours", error);
        // Fallback data
        tours.value = [
             { id: 1, title: 'Golden Triangle Special', duration: '6 Days', price: 25000, description: 'Delhi, Agra, and Jaipur in one go.', images: ['https://images.unsplash.com/photo-1548013146-72479768bada'] },
             { id: 2, title: 'Kerala Monsoon Magic', duration: '5 Days', price: 18000, description: 'Experience the rains in God\'s own country.', images: ['https://images.unsplash.com/photo-1602216056096-3b40cc0c9944'] },
             { id: 3, title: 'Himalayan Adventure', duration: '8 Days', price: 35000, description: 'Trekking and camping in the mountains.', images: ['https://plus.unsplash.com/premium_photo-1692299547777-e3e6f1975aab'] },
             { id: 4, title: 'Goa Beach Party', duration: '4 Days', price: 15000, description: 'Sun, sand, and endless parties.', images: ['https://images.unsplash.com/photo-1512343879784-a960bf40e7f2'] },
             { id: 5, title: 'Royal Rajasthan', duration: '7 Days', price: 30000, description: 'Live like a king in heritage hotels.', images: ['https://images.unsplash.com/photo-1477587458883-47145ed94245'] },
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
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
