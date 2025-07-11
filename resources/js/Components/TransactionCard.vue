<template>
  <button type="button" class="relative overflow-hidden bg-tertiary p-3 rounded-2xl flex gap-4">
    <div class="w-14 h-14 flex items-center justify-center">
      <i :class="`ti ${icon} text-2xl`"></i>
    </div>
    <div class="flex flex-col leading-4 justify-center text-left">
      <p class="text-sm">{{ name }}</p>
      <p class="font-bold">{{ amount }}</p>
      <p class="text-xs">{{ "2025-01-01 00:00:00" }}</p>
    </div>
  </button>
</template>

<script setup>
const props = defineProps({
  name: {
    type: String,
    default: 'Transaction'
  },
  icon: {
    type: String,
    default: 'ti-rotate-clockwise-2'
  },
  amount: String,
  date: {
    type: String,
    default: ""
  }
});
</script>
