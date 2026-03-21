<template>
  <div class="flex flex-col w-full">
    <label :for="props.for" class="block mb-2.5 text-sm font-medium text-heading">{{ title }}</label>
    <select :id="props.for" v-model="model" :required="required" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
      <option selected disabled :value="null" v-if="hasPlaceholder">
        Select an option...
      </option>
      <option :value="option.value" v-for="(option, i) in options" :key="i">
        {{ option.label }}
      </option>
    </select>
  </div>
</template>

<script setup>
const model = defineModel()
const props = defineProps({
  title: String,
  for: {
    type: String,
    default: null
  },
  options: {
    type: Array,
    default: []
  },
  hasPlaceholder: Boolean,
  required: Boolean
})
</script>
