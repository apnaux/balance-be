<template>
  <button :type="type" :class="[button_colors[color], 'border focus:ring-none focus:outline-none cursor-pointer font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2']">
    <slot />
  </button>
</template>

<script setup>
const props = defineProps({
  type: {
    type: String,
    default: 'button',
  },
  color: {
    type: String,
    default: 'primary',
  },
});

const button_colors = {
  'primary': 'border-primary text-primary',
  'success': 'border-success text-success',
  'warning': 'border-warning text-warning',
  'error': 'border-error text-error',
  'caution': 'border-caution text-caution',
};
</script>
