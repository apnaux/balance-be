<template>
  <nav class="sticky top-0 w-svw border-b px-3 border-[theme(fg2)] flex justify-between">
    <div class="flex gap-4 items-center">
      <p class="text-xs font-black">balancé</p>
      <div class="flex items-center">
        <button type="button" class="hover:bg-[theme(fg2)] group py-1.5 px-1.5">
          <p class="text-xs font-medium group-hover:text-[theme(bg2)]">Transactions</p>
        </button>
        <button type="button" class="hover:bg-[theme(fg2)] group py-1.5 px-1.5">
          <p class="text-xs font-medium group-hover:text-[theme(bg2)]">Settings</p>
        </button>
      </div>
    </div>
    <button type="button" class="hover:bg-[theme(fg2)] group py-1.5 px-1.5 flex gap-2 items-center">
      <i class="ti ti-chevron-down group-hover:text-[theme(bg2)]"></i>
      <p class="text-xs font-medium group-hover:text-[theme(bg2)]">{{ page.props.user.name }} (@{{
        page.props.user.username }})</p>
    </button>
  </nav>

  <!-- Content -->
  <slot />

  <div class="fixed bottom-0 w-svw flex flex-col">
    <div class="border-t border-[theme(fg2)] flex justify-between">
      <!-- Transaction Editor -->
      <div class="w-full flex flex-col justify-between">
        <!-- Current Budget -->
        <div class="p-3 flex flex-col gap-1 border-[theme(fg2)] border-b">
          <div class="flex justify-between">
            <h5 class="text-xs">Current Budget</h5>
            <h5 class="text-xs">PHP 0.00 / PHP 10,000.00</h5>
          </div>
          <div class="w-full h-4 bg-[theme(bg2)] border border-[theme(fg2)] overflow-hidden">
            <div class="bg-[theme(fg2)] h-full w-[25%] transition-all duration-500 ease-in-out"></div>
          </div>
        </div>

        <!-- Editor -->
        <div class="flex flex-col w-full">
          <input type="number" placeholder="0.00"
            class="bg-[theme(bg2)] placeholder-[theme(fg2)]/50 text-[theme(fg2)] text-8xl w-full border-0 focus:ring-0">
          <div class="flex">
            <div class="border border-[theme(fg2)] py-2 px-3 flex gap-2 items-center border-x-0">
              <p>Tag...</p>
              <i class="ti ti-chevron-up"></i>
            </div>
            <input type="text" placeholder="Transaction Description..."
              class="bg-[theme(bg2)] placeholder-[theme(fg2)]/50 border-[theme(fg2)] text-[theme(fg2)] w-full focus:ring-0 border-r-0">
          </div>
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
    </div>

    <!-- Bottom Bar -->
    <div class="px-3 bg-[theme(fg2)] flex justify-between">
      <div class="flex gap-4 items-center">
        <button type="button" class="hover:bg-[theme(bg2)] group py-1.5 px-3">
          <i class="ti ti-caret-down text-[theme(bg2)] group-hover:text-[theme(fg2)]"></i>
        </button>
        <div class="py-1.5 px-1.5 flex items-center gap-2">
          <i class="ti ti-hexagon-plus-2 text-[theme(bg2)]"></i>
          <p class="text-xs font-medium text-[theme(bg2)]">Creating new Transaction...</p>
        </div>
      </div>
      <div class="flex gap-4 items-center">
        <div class="py-1.5 px-1.5 flex items-center gap-2">
          <i class="ti ti-hexagon text-[theme(bg2)]"></i>
          <p class="text-xs font-medium text-[theme(bg2)]">20 Transactions</p>
        </div>
        <div class="py-1.5 px-1.5 flex items-center gap-2">
          <i class="ti ti-calendar text-[theme(bg2)]"></i>
          <p class="text-xs font-medium text-[theme(bg2)]">2025-06-12</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<style scoped>
input[type="number"] {
  -moz-appearance: textfield;
}
</style>