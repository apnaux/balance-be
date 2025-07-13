<template>
  <AuthenticatedLayout :blur-main="state == 'add'">
    <template #context>
      <!-- Transaction Details/Add -->
      <div class="flex flex-col justify-center gap-2 w-96">
        <div class="top-0 left-0 flex flex-col gap-4">
          <i class="ti ti-cash-plus text-4xl"></i>
          <h1 class="text-4xl">Add a<br />Transaction</h1>
        </div>

        <div class="flex flex-col gap-2">
          <h4>Amount</h4>
          <div>
            <div class="bg-white/25 border-nord-dark dark:border-white border text-nord-lightest rounded-3xl max-w-svh overflow-hidden">
              <div class="w-full h-24 rounded-3xl px-6 flex items-center">
                <p class="text-6xl font-bold">{{ "₱" }}</p>
                <input type="number" placeholder="0.00"
                  class="remove-stepper h-full w-full text-6xl font-bold outline-0 ring-0 border-0 text-nord-darker dark:text-nord-lightest dark:placeholder:text-nord-lightest/50 bg-white/0">
              </div>
            </div>
            <p class="mt-2 text-sm ml-4">Your remaining spend for this cycle is {{ "PHP 0.00" }}</p>
          </div>

          <div class="flex flex-col gap-2">
            <TextInput label="Transaction Name" type="text" key="trn_name" color="transparent" />
            <div class="flex gap-2 w-full">
              <Select label="Tag" key="trn_tag" color="transparent" />
              <Select label="Associated Account" key="trn_account" color="transparent" />
            </div>
          </div>
        </div>

        <div class="list-card mt-4">
          <button @click="changeState('list')" type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-chevron-left`]"></i>
            <p :class="[ ]">Go back</p>
          </button>
          <button type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-plus`]"></i>
            <p :class="[ ]">Create transaction</p>
          </button>
        </div>
      </div>
    </template>

    <div class="flex justify-center gap-24">
      <div class="sticky top-0 left-0 flex flex-col gap-4 w-96 h-fit pt-12">
        <i class="ti ti-cash text-4xl"></i>
        <h1 class="text-4xl">Your Budget</h1>
        <TotalSpend spend="PHP 10,000" budget="PHP 12,000" />

        <!-- Metrics -->
        <div class="list-card-x">
          <div class="p-4 flex flex-col justify-center grow">
            <p class="text-sm">Daily Spend</p>
            <p class="font-ag-fett">{{ "PHP 0" }}</p>
          </div>
          <div class="p-4 flex flex-col justify-center grow">
            <p class="text-sm">Current Cycle</p>
            <p class="font-ag-fett">{{ "2025-06-15" }}</p>
          </div>
        </div>

        <!-- Actions -->
        <h5>Actions</h5>
        <div class="list-card">
          <button @click="changeState('add')" type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-plus`]"></i>
            <p :class="[ ]">Add a Transaction</p>
          </button>
          <button type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-trash`]"></i>
            <p :class="[ ]">Delete Transactions</p>
          </button>
        </div>
      </div>
      <div class="w-[28rem] pt-12">
        <h4 class="mb-2">Recent Transactions</h4>
        <div class="list-card">
          <TransactionCard
            v-for="n in 10"
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
import Select from "@/Components/Select.vue";
import TextInput from "@/Components/TextInput.vue";
import { onMounted, ref } from "vue";
import axios from "axios";

const state = ref('list'); // ['list', 'add', 'delete']

const changeState = (to) => {
  state.value = to;
}

const getTransactions = async () => {
  await axios.post(route('api.transactions.list'))
    .then(res => {
      console.log(res.data);
    });
}

onMounted(async () => {
  await getTransactions();
});
</script>

<style scoped>
.remove-stepper[type="number"] {
  -moz-appearance: textfield;
}
</style>
