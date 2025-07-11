<template>
  <div>
    <label
      :for="key"
      class="block mb-2 text-sm font-medium text-nord-darker dark:text-nord-lightest"
      >{{ label }}</label
    >
    <input :type="type" :id="key"
      :class="[sizes[size], colors[color]]">
  </div>
</template>

<script setup>
const model = defineModel();
const props = defineProps({
  label: String,
  key: String,
  disabled: Boolean,
  error: {
    type: String,
    default: "",
  },
  icon: {
    type: String,
    default: "",
  },
  size: {
    type: String,
    default: "default",
  },
  type: {
    type: String,
    default: 'text'
  },
  color: {
    type: String,
    default: 'default'
  }
});

const colors = {
  default: 'bg-nord-lightest text-nord-darkest focus:ring-primary focus:border-primary dark:bg-nord-much-darker dark:border-nord-darker dark:placeholder-nord-dark dark:text-nord-light',
  light_transparent: 'focus:ring-primary focus:border-primary bg-white/25 text-white',
}

const sizes = {
  large: "block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base",
  default: "block w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-sm",
  small: "block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs"
};
</script>
