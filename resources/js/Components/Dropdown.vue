<template>
  <div class="relative">
    <DefaultButton v-if="useDefaultButton" :color="buttonColor" @click="show = !show" class="inline-flex gap-2"><span>{{ label }}</span><i class="ti ti-chevron-down"></i></DefaultButton>
    <button v-else @click="show = !show" class="text-body inline-flex gap-2 items-center"><span>{{ label }}</span><i class="ti ti-chevron-down"></i></button>
    <div v-if="show" :class="[dropdownPosition == 'left' ? 'left-0' : 'right-0']" class="absolute z-10 mt-3 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-72">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import DefaultButton from './Buttons/DefaultButton.vue';
const show = ref(false);
defineProps({
  label: String,
  useDefaultButton: Boolean,
  dropdownPosition: {
    type: String,
    default: 'left'
  },
  buttonColor: {
    type: String,
    default: "ghost"
  }
})
</script>
