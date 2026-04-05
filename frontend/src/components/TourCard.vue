<template>
  <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 group flex flex-col h-full border border-gray-100">
    <!-- Image Container -->
    <div class="relative h-56 overflow-hidden">
      <img :src="image" :alt="title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
      
      <!-- Best Seller Badge -->
      <div v-if="featured" class="absolute top-4 right-4 bg-orange-600 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-md">
        Best Seller
      </div>
    </div>

    <!-- Content -->
    <div class="p-5 flex flex-col flex-grow">
      
      <!-- Title -->
      <router-link :to="'/tours/' + (slug || id)">
        <h3 class="text-xl font-bold text-navy-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors font-serif" :title="title">
          {{ title }}
        </h3>
      </router-link>
      
      <!-- Duration -->
      <div class="flex items-center text-teal-600 text-sm font-medium mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ duration || '5 Days / 4 Nights' }}</span>
      </div>

      <!-- Description -->
      <p class="text-gray-600 text-sm mb-6 line-clamp-3 leading-relaxed flex-grow">
        {{ description || 'Explore the beauty of this amazing destination with our exclusive tour package including guide and meals.' }}
      </p>

      <!-- Divider -->
      <hr class="border-gray-100 mb-4" />

      <!-- Footer: Price & Action -->
      <div class="flex items-center justify-between mt-auto">
        <div>
           <p class="text-xs text-gray-500 font-medium mb-0.5">Starting from</p>
           <p class="text-2xl font-bold text-orange-600">₹{{ price }}</p>
        </div>
        
        <router-link :to="'/tours/' + (slug || id)" class="bg-navy-900 hover:bg-navy-800 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors shadow-md text-sm">
          View Deal
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  id: [Number, String],
  slug: String,
  title: String,
  price: [Number, String],
  image: String,
  location: String,
  featured: Boolean,
  duration: String,
  description: String
})
</script>

<style scoped>
/* Add custom colors if not in Tailwind config, or rely on closest standard colors */
.text-navy-900 {
    color: #1a202c; /* Replace with actual navy color if defined in config, e.g. #0f172a */
}
.bg-navy-900 {
    background-color: #1a202c;
}
.hover\:bg-navy-800:hover {
    background-color: #2d3748;
}
</style>
