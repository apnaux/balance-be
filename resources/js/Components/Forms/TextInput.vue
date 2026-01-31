<template>
  <div>
      <label :for="for" class="block mb-2.5 text-sm font-medium text-heading">{{ title }}</label>
      <input type="text" :id="for" v-model="model" :placeholder="placeholder" :required="required" :disabled="disabled"
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
