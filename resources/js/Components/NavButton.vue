<template>
  <Link as="button" type="button" :href="getRoute(href)" :disabled="href == page.props.route_name" class="cursor-pointer disabled:cursor-auto rounded-lg flex gap-4 items-center p-3"
    :class="{'bg-nord-darker dark:bg-nord-lighter': href == page.props.route_name }">
    <i :class="[`ti ti-${icon}`, {'text-nord-lighter dark:text-nord-darker': href == page.props.route_name}]"></i>
    <p :class="{'text-nord-lighter dark:text-nord-darker': href == page.props.route_name}">{{ name }}</p>
  </Link>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const props = defineProps({
  href: String,
  icon: String,
  name: String,
});

const getRoute = (link) => {
  return route(link)
}
</script>
