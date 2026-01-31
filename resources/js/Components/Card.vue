<template>
  <div class="w-full bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs" :class="class">
    <slot />
  </div>
</template>

<script setup>
defineProps({
  class: {
    type: String,
    default: "max-w-sm"
  }
})
</script>
