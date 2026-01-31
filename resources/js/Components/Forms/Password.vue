<template>
  <div>
    <label :for="for" class="block mb-2.5 text-sm font-medium text-heading">{{ title }}</label>
    <component
      :required="required"
      :is="showToggle ? 'div' : 'input'" placeholder="•••••••••" v-model="model" :disabled="disabled"
      :class="showToggle ? 'overflow-hidden flex items-center focus-within:ring-brand focus-within:border-brand' : 'px-3 py-2.5 placeholder:text-body focus:ring-brand focus:border-brand'"
      class="bg-neutral-secondary-medium border border-default-medium  text-heading text-sm rounded-base block w-full shadow-xs">
      <template v-if="showToggle">
        <input :type="toggle ? 'text' : 'password'" v-model="model" :required="required" :disabled="disabled" class="placeholder:text-body border-none focus:ring-0 focus:border-0 bg-neutral-secondary-medium px-3 py-2.5 w-full" placeholder="•••••••••">
        <button type="button" @click="toggle = !toggle"><i class="ti pr-3" :class="toggle ? 'ti-eye-off' : 'ti-eye'"></i></button>
      </template>
    </component>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const model = defineModel();
const toggle = ref(false);
defineProps({
  showToggle: Boolean,
  required: Boolean,
  disabled: Boolean,
  for: {
    type: String,
    default: '',
  },
  title: {
    type: String,
    default: 'Your password'
  }
})
</script>
