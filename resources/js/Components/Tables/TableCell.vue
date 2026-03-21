<template>
  <component :is="type" :scope="scope" :class="[cellFormat[formatting], center ? 'text-center' : '']">
    <slot></slot>
  </component>
</template>

<script setup>
const cellFormat = {
  th: 'px-6 py-3 font-medium',
  td: 'px-6 py-4',
  tdHighlight: 'px-6 py-4 font-medium text-heading whitespace-nowrap'
};

defineProps({
  type: {
    type: String,
    default: 'td'
  },
  scope: {
    type: String,
    default: null
  },
  center: Boolean,
  formatting: {
    type: String,
    default: 'td'
  }
});
</script>
