<template>
  <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
    <!-- Filters Group -->
    <div class="space-y-6">
      
      <!-- Destination -->
      <div class="border-b border-gray-100 pb-6">
        <label class="flex items-center text-sm font-medium text-gray-500 mb-2">
            <span class="mr-2 text-yellow-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
            </span>
          Destination
        </label>
        <div class="relative">
          <button @click="isDestinationOpen = !isDestinationOpen" class="w-full flex justify-between items-center py-2 text-gray-900 font-bold">
            Select Destination
            <svg :class="{'rotate-180': isDestinationOpen}" class="h-5 w-5 text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
           <div v-show="isDestinationOpen" class="mt-2 space-y-2 pl-2">
             <label class="flex items-center text-gray-600">
                 <input 
                    type="radio" 
                    name="destination" 
                    :value="null" 
                    v-model="selectedDestination"
                    @change="emitFilter"
                    class="mr-2 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500"
                > 
                All Destinations
             </label>
             <label v-for="dest in destinations" :key="dest.id" class="flex items-center text-gray-600">
                 <input 
                    type="radio" 
                    name="destination" 
                    :value="dest.id" 
                    v-model="selectedDestination"
                    @change="emitFilter"
                    class="mr-2 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500"
                > 
                {{ dest.name }}
             </label>
           </div>
        </div>
      </div>

      <!-- Category -->
      <div class="border-b border-gray-100 pb-6">
        <label class="flex items-center text-sm font-medium text-gray-500 mb-2">
            <span class="mr-2 text-indigo-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </span>
          Category
        </label>
         <div class="space-y-2 pl-2">
             <label class="flex items-center text-gray-600">
                 <input type="radio" name="category" :value="null" v-model="selectedCategory" @change="emitFilter" class="mr-2 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                 All Categories
             </label>
             <label v-for="cat in categories" :key="cat" class="flex items-center text-gray-600">
                 <input type="radio" name="category" :value="cat" v-model="selectedCategory" @change="emitFilter" class="mr-2 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                 {{ cat }}
             </label>
         </div>
      </div>

      <!-- Price Range -->
      <div class="border-b border-gray-100 pb-6">
        <h3 class="font-bold text-gray-900 mb-4">Max Price: ₹{{ maxPrice }}</h3>
        <input 
            type="range" 
            min="0" 
            max="100000" 
            step="1000" 
            v-model="maxPrice" 
            @change="emitFilter"
            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
        >
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const emit = defineEmits(['filter-change']);

const isDestinationOpen = ref(true);
const destinations = ref([]);
const selectedDestination = ref(null);
const selectedCategory = ref(null);
const maxPrice = ref(200000); // Increased max price

const categories = ['General', 'Adventure', 'Honeymoon', 'Family', 'Luxury', 'Spiritual'];

const fetchDestinations = async () => {
    try {
        const response = await api.get('/destinations');
        destinations.value = response.data;
    } catch (error) {
        console.error("Failed to load destinations", error);
    }
};

const emitFilter = () => {
    emit('filter-change', {
        destination_id: selectedDestination.value,
        category: selectedCategory.value,
        max_price: maxPrice.value
    });
};

onMounted(() => {
    fetchDestinations();
});
</script>
