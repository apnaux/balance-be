<template>
  <div>
    <label :for="for" class="block mb-2.5 text-sm font-medium text-heading">{{ title }}</label>
    <input type="number" :id="for" v-model="model" :placeholder="placeholder" :required="required"
      :max="max" :min="min" :step="step" :disabled="disabled"
      class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
    />
  </div>
</template>

<script setup>
const model = defineModel();
defineProps({
  title: String,
  required: Boolean,
  disabled: Boolean,
  min: {
    type: Number,
    default: 0
  },
  max: {
    type: Number,
    default: 0
  },
  step: {
    type: Number,
    default: 1
  },
  for: {
    type: String|null,
    default: null
  },
  placeholder: {
    type: String,
    default: ""
  }
});
</script>
