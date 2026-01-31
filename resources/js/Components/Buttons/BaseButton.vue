<template>
  <button :class="[sizes['base'], {'w-full': fluid}, 'focus:outline-none font-medium', ...extra]">
    <slot />
  </button>
</template>

<script setup>
defineProps({
  fluid: Boolean,
  size: {
    type: String,
    default: 'base'
  },
  extra: {
    type: Array,
    default: []
  }
});

const sizes = {
  xl: 'text-base px-6 py-3.5',
  lg: 'text-base px-5 py-3',
  base: 'leading-5 text-sm px-4 py-2.5',
  sm: 'leading-5 text-sm px-3 py-2',
  xs: 'leading-5 text-xs px-3 py-1.5'
};
</script>
