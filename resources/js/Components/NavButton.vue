<template>
  <Link as="button" type="button" :href="href" class="flex gap-4 items-center p-3">
    <i :class="`ti ti-${icon}`"></i>
    <p>{{ name }}</p>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({
  href: String,
  icon: String,
  name: String,
});
</script>
