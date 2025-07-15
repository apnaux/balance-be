<template>
  <div class="w-full">
    <label
      :for="key"
      class="block mb-2 text-sm font-medium text-nord-darker dark:text-nord-lightest"
      >{{ label }}</label>
    <select v-model="model" :id="key" :class="[sizes[size], colors[color]]">
      <option disabled selected>Select...</option>
      <option v-for="option in options" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <p v-if="error" class="text-warning text-sm mt-2">
      <i class="ti ti-alert-triangle text-warning"></i> {{ error }}
    </p>
  </div>
</template>

<script setup>
const model = defineModel()
const props = defineProps({
  label: String,
  options: Array,
  error: String,
  size: {
    type: String,
    default: "default",
  },
  color: {
    type: String,
    default: 'default'
  }
});

const colors = {
  default: 'bg-nord-lightest text-nord-darkest focus:ring-primary focus:border-primary dark:bg-nord-much-darker dark:border-nord-darker dark:placeholder-nord-dark dark:text-nord-light',
  transparent: 'focus:ring-primary focus:border-primary bg-white/25 text-nord-darker placeholder-nord-darker/50 dark:text-white dark:placeholder-white/50',
}

const sizes = {
  large: "block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base",
  default: "block w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-sm",
  small: "block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs"
};
</script>
