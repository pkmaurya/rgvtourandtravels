<template>
  <div class="home">
    <HomeOffersBanner />
    <Hero />
    
    <!-- About Section -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base text-orange-600 font-semibold tracking-wide uppercase">About Us</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            Creating Memories That Last a Lifetime
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
            At RGV Tour and Travels, we don't just plan trips; we craft experiences. 
            From the snow-capped Himalayas to the tropical beaches of the south, 
            we guide you through the heart of India's diverse culture and heritage.
          </p>
        </div>
      </div>
    </section>

    <!-- Top Destinations with Horizontal Scroll -->
    <TopDestinations />

    <!-- Special Offers -->
    <ActiveOffers />

    <!-- Offer Packages with Horizontal Scroll -->
    <OfferPackages />

    <!-- Why Choose Us -->
    <div class="bg-gray-50 py-16 border-t border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-12">Why Choose Us?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">
                <div class="text-5xl mb-4">🏆</div>
                <h3 class="text-xl font-bold mb-2">Best Price Guarantee</h3>
                <p class="text-gray-600">We offer the most competitive prices for luxury experiences.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">
                <div class="text-5xl mb-4">🛡️</div>
                <h3 class="text-xl font-bold mb-2">Safe & Secure</h3>
                <p class="text-gray-600">Verified hotels and transport for your safety.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">
                <div class="text-5xl mb-4">🧑‍✈️</div>
                <h3 class="text-xl font-bold mb-2">Expert Guides</h3>
                <p class="text-gray-600">Travel with locals who know the history and culture.</p>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Hero from '../components/Hero.vue';
import TopDestinations from '../components/TopDestinations.vue';
import OfferPackages from '../components/OfferPackages.vue';
import HomeOffersBanner from '../components/HomeOffersBanner.vue';
import ActiveOffers from '../components/ActiveOffers.vue';
</script>
