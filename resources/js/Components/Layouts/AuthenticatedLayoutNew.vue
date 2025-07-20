<template>
  <nav class="fixed top-0 w-svw border-b py-1.5 px-3 border-[theme(fg2)] flex gap-6">
    <p class="text-xs font-medium">balancé</p>
    <div class="flex gap-3">
      <p class="text-xs font-medium">Transactions</p>
      <p class="text-xs font-medium">Settings</p>
      <p class="text-xs font-medium">About</p>
    </div>
  </nav>
  <slot />
  <nav class="fixed bottom-0 w-svw border-t border-[theme(fg2)] flex justify-between">
    <!-- Transaction Editor -->
    <div class="h-72 w-full p-3 flex flex-col gap-1">
      <div class="flex justify-between">
        <h5 class="text-xs">Current Budget</h5>
        <h5 class="text-xs">PHP 0.00 / PHP 10,000.00</h5>
      </div>
      <div class="w-full h-4 bg-[theme(bg2)] border border-[theme(fg2)] overflow-hidden">
        <div class="bg-[theme(fg2)] h-full w-[50%] transition-all duration-500 ease-in-out"></div>
      </div>
    </div>

    <!-- NumPad -->
    <div class="grid grid-cols-3 w-[40rem]">
      <button v-for="n in 10" class="p-8 text-[theme(fg2)] border-l border-b">
        {{ (n == 10 ? 0 : n) }}
      </button>
      <button class="p-8 text-[theme(fg2)] border-l border-b row-span-2"> . </button>
      <button class="p-8 text-[theme(fg2)] border-l border-b row-span-2"> Add + </button>
    </div>
  </nav>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>