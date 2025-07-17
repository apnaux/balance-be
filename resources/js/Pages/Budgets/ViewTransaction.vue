<template>
<div class="flex flex-col justify-center gap-2 w-96">
  <div class="top-0 left-0 flex flex-col gap-4">
    <i :class="`ti text-4xl ${transaction.tag.icon}`"></i>
    <h1 class="text-4xl">Transaction<br />Details</h1>
  </div>

  <div class="flex flex-col gap-2">
    <h4>Amount</h4>
    <div class="bg-white/25 border-nord-dark dark:border-white border text-nord-lightest rounded-3xl max-w-svh overflow-hidden">
      <div class="w-full h-24 rounded-3xl px-3 flex items-center">
        <input :value="transaction.formatted_amount" type="text" placeholder="0.00" readonly
          class="remove-stepper h-full w-full text-6xl font-bold outline-0 ring-0 border-0 text-nord-darker dark:text-nord-lightest dark:placeholder:text-nord-lightest/50 bg-white/0">
      </div>
    </div>
    <div class="list-card mt-2">
      <div class="list-item flex-col">
        <h6>Transaction Name</h6>
        <p>{{ transaction.name }}</p>
      </div>
      <div class="list-item flex-col">
        <h6>Tag</h6>
        <p>{{ transaction.tag.name }}</p>
      </div>
      <div class="list-item flex-col">
        <h6>Associated Account</h6>
        <p>{{ transaction.transactable.name }}</p>
      </div>
      <div class="list-item flex-col">
        <h6>Created at</h6>
        <p>{{ getLocalDtFormat(transaction.created_at) }}</p>
      </div>
    </div>
  </div>

  <div class="list-card-x mt-2">
    <button @click="emits('back')" type="button" class="w-1/3">
      <i :class="[`ti ti-chevron-left`]"></i>
      <p :class="[`leading-5`]">Go back</p>
    </button>
    <button type="button" @click="emits('edit')" class="w-1/3">
      <i :class="[`ti ti-edit`]"></i>
      <p :class="[`leading-5`]">Edit</p>
    </button>
    <button type="button" @click="emits('delete')" class="w-1/3 bg-warning/25">
      <i :class="[`ti ti-trash text-warning contrast-150`]"></i>
      <p :class="[`leading-5 text-warning contrast-150`]">Delete</p>
    </button>
  </div>
</div>
</template>
<script setup>
import { getLocalDtFormat } from "@/Composables/formatters";

const props = defineProps({
  transaction: Object
});

const emits = defineEmits(['back', 'edit', 'delete']);
</script>
