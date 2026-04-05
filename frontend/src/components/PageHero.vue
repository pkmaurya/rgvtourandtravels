<template>
  <div class="relative bg-gray-900 h-[40vh] min-h-[300px] flex items-center">
    <!-- Background Image -->
    <div class="absolute inset-0 overflow-hidden">
      <img 
        :src="backgroundImage" 
        alt="Hero Background" 
        class="w-full h-full object-cover opacity-60"
      >
      <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
      <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-lg">
        {{ title }}
      </h1>
      <p v-if="subtitle" class="text-xl text-gray-200 max-w-2xl mx-auto drop-shadow-md">
        {{ subtitle }}
      </p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  backgroundImage: {
     type: String,
     default: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800' // Default travel image
  }
})
</script>
