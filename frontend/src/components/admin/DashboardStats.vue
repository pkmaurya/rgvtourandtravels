<template>
  <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
    <div>
      <p class="text-sm font-medium text-gray-500 mb-1">{{ title }}</p>
      <h3 class="text-2xl font-bold text-gray-900">{{ value }}</h3>
      <p class="text-xs text-gray-400 mt-1">{{ subtext }}</p>
    </div>
    <div :class="`p-3 rounded-xl ${colorClass}`">
        <slot name="icon"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: String,
  subtext: String,
  colorClass: {
      type: String,
      default: 'bg-indigo-50 text-indigo-600'
  }
})
</script>
