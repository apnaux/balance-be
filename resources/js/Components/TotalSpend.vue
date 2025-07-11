<template>
  <div :class="[{'cursor-pointer': !disableClick}, hasOverspent ? 'bg-warning' : 'bg-tertiary']"
    class="ion-activatable relative overflow-hidden select-none rounded-2xl h-32 leading-5 flex items-center justify-between px-6">
    <div class="flex flex-col">
      <p class="text-sm font-bold leading-5">
        <i class="ti ti-alert-triangle" v-if="hasOverspent"></i>
        {{ title }}
      </p>
      <p class="text-4xl font-bold">{{ spend }}</p>
      <p class="">of {{ budget }}</p>
    </div>
    <i class="ti ti-chevron-right text-xl" v-if="!disableClick"></i>
  </div>
</template>

<script setup>
const props = defineProps({
  spend: String,
  budget: String,
  hasOverspent: Boolean,
  disableClick: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: "Your Total Spend"
  }
});
</script>
