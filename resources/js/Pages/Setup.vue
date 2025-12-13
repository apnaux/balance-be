<template>
  <div class="w-svw h-svh flex items-center justify-center">

  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  errors: Object,
});

const form = useForm({
  first_run: true,
  total_income: 0,
  to_save: 0,
  cycle_cutoff: 1,
  currency: 'PHP',
  timezone: 'Asia/Manila'
});
</script>
