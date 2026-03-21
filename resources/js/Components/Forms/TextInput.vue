<template>
  <div class="w-full">
    <label :for="props.for" class="block mb-2.5 text-sm font-medium text-heading">{{ title }}</label>
    <input type="text" :id="props.for" v-model="model" :placeholder="placeholder" :required="required" :disabled="disabled" :class="{'w-full': fluid}"
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
  for: {
    type: String,
    default: ""
  },
  placeholder: {
    type: String,
    default: ""
  }
});
</script>
