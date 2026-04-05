<template>
  <header class="flex justify-end items-center h-16 bg-white border-b border-gray-200 px-6 fixed top-0 right-0 left-0 md:left-64 z-40 shadow-sm">
    <!-- Right Side Actions -->
    <div class="ml-4 flex items-center md:ml-6 space-x-4">
      
      <!-- Notifications Dropdown -->
      <Menu as="div" class="relative">
        <MenuButton class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <span class="sr-only">View notifications</span>
          <div class="relative">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
              <span v-if="notifications.length > 0" class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
          </div>
        </MenuButton>
        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
          <MenuItems class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <div class="px-4 py-2 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            </div>
            <div class="max-h-64 overflow-y-auto">
                <div v-if="notifications.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                    No new notifications
                </div>
                <div v-else>
                    <MenuItem v-for="notification in notifications" :key="notification.id" v-slot="{ active }">
                        <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-4 py-3']">
                            <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ notification.message }}</p>
                        </a>
                    </MenuItem>
                </div>
            </div>
            <div class="border-t border-gray-100">
                <MenuItem v-slot="{ active }">
                    <button @click="$emit('navigate', 'notifications')" :class="[active ? 'bg-gray-50' : '', 'block w-full text-center px-4 py-2 text-sm text-indigo-600 font-medium']">
                        View all
                    </button>
                </MenuItem>
            </div>
          </MenuItems>
        </transition>
      </Menu>

      <!-- Messages (Emails) Dropdown -->
      <Menu as="div" class="relative">
        <MenuButton class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
           <span class="sr-only">View messages</span>
           <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
           <span v-if="messages.length > 0" class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
        </MenuButton>
        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
          <MenuItems class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
             <div class="px-4 py-2 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Messages</h3>
            </div>
             <div class="max-h-64 overflow-y-auto">
                <div v-if="messages.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                    No new messages
                </div>
                <div v-else>
                    <MenuItem v-for="message in messages" :key="message.id" v-slot="{ active }">
                        <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-4 py-3']">
                            <div class="flex justify-between">
                                <p class="text-sm font-medium text-gray-900">{{ message.sender }}</p>
                                <span class="text-xs text-gray-400">{{ message.time }}</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">{{ message.text }}</p>
                        </a>
                    </MenuItem>
                </div>
            </div>
             <div class="border-t border-gray-100">
                <MenuItem v-slot="{ active }">
                    <button @click="$emit('navigate', 'inquiries')" :class="[active ? 'bg-gray-50' : '', 'block w-full text-center px-4 py-2 text-sm text-indigo-600 font-medium']">
                        View all
                    </button>
                </MenuItem>
            </div>
          </MenuItems>
        </transition>
      </Menu>

      <!-- Profile Dropdown -->
      <Menu as="div" class="relative ml-3">
        <div>
          <MenuButton class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="sr-only">Open user menu</span>
            <img class="h-8 w-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
            <span class="ml-3 hidden md:block text-sm font-medium text-gray-700">Admin</span>
            <svg class="ml-1 hidden md:block h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </MenuButton>
        </div>
        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
          <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <MenuItem v-slot="{ active }">
              <button @click="$emit('logout')" :class="[active ? 'bg-gray-100' : '', 'block w-full text-left px-4 py-2 text-sm text-gray-700']">
                  Logout
              </button>
            </MenuItem>
          </MenuItems>
        </transition>
      </Menu>
    </div>
  </header>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'

defineProps({
    user: Object,
    notifications: {
        type: Array,
        default: () => []
    },
    messages: {
        type: Array,
        default: () => []
    }
})

defineEmits(['navigate', 'logout'])
</script>
