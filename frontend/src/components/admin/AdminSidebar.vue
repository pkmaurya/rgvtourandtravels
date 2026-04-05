<template>
  <aside :class="[isCollapsed ? 'w-20' : 'w-64', 'hidden md:flex flex-col bg-white border-r border-gray-200 min-h-screen fixed left-0 top-0 bottom-0 z-50 transition-all duration-300']">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 border-b border-gray-200 relative">
      <router-link to="/" class="flex items-center gap-2">
         <div class="bg-brand-secondary rounded-full p-1.5">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
         </div>
         <span v-if="!isCollapsed" class="text-2xl font-bold text-brand-secondary font-serif">Admin</span>
      </router-link>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 overflow-x-hidden">
      <ul class="space-y-1 px-3">
        <li>
          <a href="#" @click.prevent="$emit('navigate', 'dashboard')" 
             :class="[activeTab === 'dashboard' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
             :title="isCollapsed ? 'Dashboard' : ''">
            <svg :class="[activeTab === 'dashboard' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Dashboard</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'bookings')" 
             :class="[activeTab === 'bookings' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
             :title="isCollapsed ? 'Booking History' : ''">
            <svg :class="[activeTab === 'bookings' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Booking History</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'tours')" 
            :class="[activeTab === 'tours' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
            :title="isCollapsed ? 'Tours Management' : ''">
            <svg :class="[activeTab === 'tours' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Tours Management</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'offers')" 
            :class="[activeTab === 'offers' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
            :title="isCollapsed ? 'Offers & Discounts' : ''">
            <svg :class="[activeTab === 'offers' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Offers & Discounts</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'payments')" 
            :class="[activeTab === 'payments' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
            :title="isCollapsed ? 'Payments' : ''">
            <svg :class="[activeTab === 'payments' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Payments</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'destinations')" 
            :class="[activeTab === 'destinations' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
            :title="isCollapsed ? 'Destinations' : ''">
            <svg :class="[activeTab === 'destinations' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Destinations</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'users')" 
            :class="[activeTab === 'users' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
            :title="isCollapsed ? 'Users' : ''">
            <svg :class="[activeTab === 'users' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Users</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'inquiries')" 
            :class="[activeTab === 'inquiries' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
            :title="isCollapsed ? 'Inquiries' : ''">
            <svg :class="[activeTab === 'inquiries' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Inquiries</span>
          </a>
        </li>
         <li>
          <a href="#" @click.prevent="$emit('navigate', 'chatbot')" 
            :class="[activeTab === 'chatbot' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors']"
            :title="isCollapsed ? 'Chatbot' : ''">
            <svg :class="[activeTab === 'chatbot' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Chatbot</span>
          </a>
        </li>
         <li>
          <a href="#" @click="$emit('navigate', 'settings')" :class="[activeTab === 'settings' ? 'bg-gray-100 text-gray-900 border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium transition-colors']"
             :title="isCollapsed ? 'Settings' : ''">
            <svg class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Settings</span>
          </a>
        </li>
      </ul>

      <div class="mt-8 px-3">
         <button @click="$emit('logout')" class="w-full group flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors"
                 :title="isCollapsed ? 'Logout' : ''">
            <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span v-if="!isCollapsed" class="whitespace-nowrap">Logout</span>
         </button>
      </div>
    </nav>
    
    <!-- Sidebar Toggle Button (Bottom) -->
    <div class="border-t border-gray-200 p-4 flex justify-center">
        <button @click="$emit('toggle')" class="text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg :class="{'rotate-180': isCollapsed}" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>
    </div>
  </aside>
</template>

<script setup>
defineProps({
    activeTab: String,
    isCollapsed: Boolean
})
defineEmits(['navigate', 'logout', 'toggle'])
</script>
