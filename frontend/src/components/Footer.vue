<template>
  <footer class="bg-gray-900 text-white pt-16 pb-8 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
        
        <!-- Brand Section -->
        <div>
          <h2 class="text-2xl font-bold text-brand-secondary mb-4 flex items-center gap-2 font-serif">
            <span>RGV Tour and Travels</span>
          </h2>
          <p class="text-gray-400 leading-relaxed mb-6">
            Embark on a journey through the heart of India. We craft unforgettable experiences, blending luxury with authentic cultural immersion.
          </p>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">Facebook</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">Instagram</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.409-.06 3.809-.06h.63zm1.418 2.003l-.35.001c-2.627 0-3.004.01-4.061.059-1.096.05-1.697.284-2.187.475-.544.209-.92.482-1.309.872a3.785 3.785 0 00-.872 1.309c-.19.49-.425 1.091-.475 2.187-.049 1.057-.059 1.434-.059 4.061 0 2.627.01 3.004.059 4.061.05 1.096.284 1.697.475 2.187.475 1.057.049 1.434.059 4.061.059 2.627 0 3.004-.01 4.061-.059 1.096-.05 1.697-.284 2.187-.475.544-.209.92-.482 1.309-.872.389-.39.663-.765.872-1.309.19-.49.425-1.091.475-2.187.049-1.057.059-1.434.059-4.061 0-2.627-.01-3.004-.059-4.061-.05-1.096-.284-1.697-.475-2.187-.209-.544-.482-.92-.872-1.309a3.785 3.785 0 00-1.309-.872c-.49-.19-1.091-.425-2.187-.475-1.043-.048-1.41-.059-3.832-.059zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">Twitter</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-semibold text-white mb-4">Quick Links</h3>
          <ul class="space-y-3">
            <li><router-link to="/" class="text-gray-400 hover:text-brand-secondary transition-colors">Home</router-link></li>
            <li><router-link to="/tours" class="text-gray-400 hover:text-brand-secondary transition-colors">Tour Packages</router-link></li>
            <li><router-link to="/destinations" class="text-gray-400 hover:text-brand-secondary transition-colors">Destinations</router-link></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-secondary transition-colors">About Us</a></li>
            <li><a href="#" class="text-gray-400 hover:text-brand-secondary transition-colors">Contact</a></li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
           <h3 class="text-lg font-semibold text-white mb-4">Contact Us</h3>
           <ul class="space-y-4">
             <li class="flex items-start">
               <svg class="h-6 w-6 text-brand-secondary mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
               <span class="text-gray-400">123 Tourism Lane, Connaught Place, New Delhi, India</span>
             </li>
             <li class="flex items-center">
               <svg class="h-6 w-6 text-brand-secondary mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
               <span class="text-gray-400">+91 98765 43210</span>
             </li>
             <li class="flex items-center">
               <svg class="h-6 w-6 text-brand-secondary mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
               <span class="text-gray-400">info@rgvtourandtravels.com</span>
             </li>
           </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 class="text-lg font-semibold text-white mb-4">Newsletter</h3>
          <p class="text-gray-400 mb-4">Subscribe to our newsletter for the latest updates and exclusive offers.</p>
          <form class="flex flex-col gap-2">
            <input type="email" placeholder="Enter your email" class="bg-gray-800 text-white px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-secondary border border-gray-700 placeholder-gray-500" />
            <button type="submit" class="bg-gradient-to-r from-brand-secondary to-brand-accent text-white font-bold py-2 px-4 rounded-lg hover:from-brand-accent hover:to-brand-secondary transition-all">Subscribe</button>
          </form>
        </div>

      </div>

      <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
        <p class="text-gray-500 text-sm">
          &copy; {{ new Date().getFullYear() }} RGV Tour and Travels. All rights reserved.
        </p>
        <div class="flex space-x-6 mt-4 md:mt-0">
          <a href="#" class="text-gray-500 hover:text-white text-sm">Privacy Policy</a>
          <a href="#" class="text-gray-500 hover:text-white text-sm">Terms of Service</a>
          <a href="#" class="text-gray-500 hover:text-white text-sm">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
