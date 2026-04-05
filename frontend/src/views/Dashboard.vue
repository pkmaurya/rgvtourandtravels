<template>
  <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">My Dashboard</h1>
      
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900">My Bookings</h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">History and upcoming trips.</p>
          </div>
          <div class="border-t border-gray-200">
              <dl>
                  <!-- In a real app, fetch bookings from API /api/bookings?user_id=... -->
                  <!-- For now, showing static mock or empty state as we didn't implement Get Bookings API for User -->
                  <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                      <dt class="text-sm font-medium text-gray-500">Note</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Booking history feature coming soon! (Backend API for specific user bookings needed)</dd>
                  </div>
              </dl>
          </div>
      </div>
  </div>
</template>

<script setup>
// Placeholder for now
</script>
