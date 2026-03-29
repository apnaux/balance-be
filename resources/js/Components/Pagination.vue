<template>
  <nav aria-label="Pagination" class="flex gap-4 items-center">
    <div class="inline-flex rounded-base shadow-xs -space-x-px" role="group">
      <button @click="emits('previous')"
        data-tooltip-target="tooltip-previous"
        type="button"
        class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium rounded-s-base box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-3 focus:ring-neutral-tertiary leading-5 w-9 h-9 focus:outline-none"
      >
        <IconChevronLeft :size="18" />
      </button>
      <button
        type="button"
        class="inline-flex shrink-0 text-sm items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading leading-5 px-3 h-9 focus:outline-none"
      >
        1 of {{ props.lastPage }}
      </button>
      <button @click="emits('next')"
        type="button"
        class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium rounded-e-base box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-3 focus:ring-neutral-tertiary leading-5 w-9 h-9 focus:outline-none"
      >
        <IconChevronRight :size="18" />
      </button>
    </div>

    <div class="bg-body/50 w-[1px] h-4"></div>

    <form class="mx-auto flex items-center space-x-3" @submit.prevent="changePage()">
      <div class="flex items-center space-x-2">
        <label for="visitors" class="text-sm font-medium text-heading shrink-0">Go to</label>
        <input v-model="pageField" type="text" id="visitors" class="bg-neutral-secondary-medium w-10 border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block px-2.5 py-2 shadow-xs placeholder:text-body" placeholder="99" />
        <span class="text-sm font-medium text-heading">page</span>
      </div>
    </form>
  </nav>
</template>

<script setup>
import { IconChevronLeft, IconChevronRight } from "@tabler/icons-vue";
import { ref } from "vue";

const page = defineModel();
const props = defineProps(['lastPage']);
const emits = defineEmits(['previous', 'next']);

const pageField = ref();

const changePage = () => {
  if (pageField.value >= 1 && pageField.value <= props.lastPage) {
    page.value = pageField.value;
    pageField.value = null;
  } else {
    console.log('Page not found...');
  }
}
</script>
