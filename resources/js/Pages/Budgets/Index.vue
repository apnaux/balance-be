<template>
  <AuthenticatedLayout>
    <div class="flex justify-center gap-24">
      <div class="sticky top-0 left-0 flex flex-col gap-4 w-96 h-fit pt-12">
        <i class="ti ti-cash text-4xl"></i>
        <h1 class="text-4xl">Your Budget</h1>
        <TotalSpend spend="PHP 10,000" budget="PHP 12,000" />

        <!-- Metrics -->
        <div class="flex gap-2 text-md-dark-on-secondary-container">
          <div
            class="bg-secondary p-4 flex flex-col justify-center rounded-2xl grow"
          >
            <p class="text-sm text-nord-darkest">Daily Spend</p>
            <p class="font-ag-fett text-nord-darkest">{{ "PHP 0" }}</p>
          </div>
          <div
            class="bg-secondary p-4 flex flex-col justify-center rounded-2xl grow"
          >
            <p class="text-sm text-nord-darkest">Current Cycle</p>
            <p class="font-ag-fett text-nord-darkest">{{ "2025-06-15" }}</p>
          </div>
        </div>

        <!-- Recurring Transactions -->

        <!-- Actions -->
        <h5>Actions</h5>
        <div class="flex flex-col rounded-xl border border-white bg-nord-lightest/10 [&>*:not(:last-child)]:border-b [&>*:not(:last-child)]:border-dashed [&>*:not(:last-child)]:border-white">
          <button type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-plus`]"></i>
            <p :class="[ ]">Add a Transaction</p>
          </button>
          <button type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-edit`]"></i>
            <p :class="[ ]">Edit a Transaction</p>
          </button>
          <button type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-trash`]"></i>
            <p :class="[ ]">Delete Transactions</p>
          </button>
        </div>
      </div>
      <div class="w-[28rem] pt-12">
        <h4 class="mb-2">Recent Transactions</h4>
        <div class="flex flex-col gap-2">
          <TransactionCard
            v-for="n in 30"
            name="Food"
            amount="PHP 100.00"
            icon="ti-user"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Components/Layouts/AuthenticatedLayout.vue";
import TotalSpend from "@/Components/TotalSpend.vue";
import TransactionCard from "@/Components/TransactionCard.vue";
</script>
