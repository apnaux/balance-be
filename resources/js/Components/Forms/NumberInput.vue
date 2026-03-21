<template>
  <div class="w-full">
    <label :for="props.for" class="block mb-2.5 text-sm font-medium text-heading">{{ title }}</label>
    <input type="number" :id="props.for" v-model="model" :placeholder="placeholder" :required="required"
      :max="max" :min="min" :step="step" :disabled="disabled" :class="{'w-full': fluid}"
      class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
    />
  </div>
</template>

<script setup>
const model = defineModel();
const props = defineProps({
  title: String,
  required: Boolean,
  disabled: Boolean,
  fluid: Boolean,
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
    type: String,
    default: null
  },
  placeholder: {
    type: String,
    default: ""
  }
});
</script>
