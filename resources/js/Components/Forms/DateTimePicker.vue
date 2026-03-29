<template>
  <VueDatePicker v-model="model"></VueDatePicker>
</template>

<script setup>
import { VueDatePicker } from '@vuepic/vue-datepicker';
const model = defineModel()
</script>
