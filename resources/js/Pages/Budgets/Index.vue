<template>
  <AuthenticatedLayout :blur-main="state == 'add'">
    <template #context>
      <!-- Transaction Details/Add -->
      <form @submit.prevent="createTransaction" class="flex flex-col justify-center gap-2 w-96">
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
                <input v-model="form.amount" type="number" placeholder="0.00"
                  class="remove-stepper h-full w-full text-6xl font-bold outline-0 ring-0 border-0 text-nord-darker dark:text-nord-lightest dark:placeholder:text-nord-lightest/50 bg-white/0">
              </div>
            </div>
            <p class="mt-2 text-sm ml-4">Your remaining spend for this cycle is {{ "PHP 0.00" }}</p>
          </div>

          <div class="flex flex-col gap-2">
            <TextInput v-model="form.name" :error="errors.name ? errors.name[0] : ''" label="Transaction Name" type="text" key="trn_name" color="transparent" />
            <div class="flex gap-2 w-full">
              <Select v-model="form.tag_id" :error="errors.tag_id ? errors.tag_id[0] : ''" :options="tags" label="Tag" key="trn_tag" color="transparent" />
              <Select v-model="form.transactable_id" :error="errors.transactable_id ? errors.transactable_id[0] : ''" :options="accounts" label="Associated Account" key="trn_account" color="transparent" />
            </div>
          </div>
        </div>

        <div class="list-card mt-4">
          <button @click="changeState('list')" type="button" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-chevron-left`]"></i>
            <p :class="[ ]">Go back</p>
          </button>
          <button type="submit" class="cursor-pointer disabled:cursor-auto p-3 flex gap-3 items-center">
            <i :class="[`ti ti-plus`]"></i>
            <p :class="[ ]">Create transaction</p>
          </button>
        </div>
      </form>
    </template>

    <div class="flex justify-center gap-24">
      <div class="sticky top-0 left-0 flex flex-col gap-4 w-96 h-fit pt-12">
        <i class="ti ti-cash text-4xl"></i>
        <h1 class="text-4xl">Your Budget</h1>
        <TotalSpend :spend="summary.statement_balance" :budget="summary.allocated_budget" v-if="summary"/>

        <!-- Metrics -->
        <div class="list-card-x"  v-if="summary">
          <div class="p-4 flex flex-col justify-center grow">
            <p class="text-sm">Daily Spend</p>
            <p class="font-ag-fett">{{ summary.daily_spend }}</p>
          </div>
          <div class="p-4 flex flex-col justify-center grow">
            <p class="text-sm">Current Cycle</p>
            <p class="font-ag-fett">{{ summary.statement_date }}</p>
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
          <template v-if="transactions && transactions.data.length > 0">
            <TransactionCard
              v-for="transaction in transactions.data"
              :name="transaction.name"
              :amount="transaction.formatted_amount"
              :icon="transaction.tag.icon"
              :date="transaction.created_at"
            />
          </template>
          <template v-else>
            <div class="text-center p-4">
              <p>No transactions found.</p>
            </div>
          </template>
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

import { onMounted, reactive, ref, shallowReactive, shallowRef, watch } from "vue";
import axios from "axios";

const state = ref('list'); // ['list', 'add', 'delete']
const transactions = shallowRef(null);
const tags = shallowRef(null);
const accounts = shallowRef(null);
const summary = shallowRef(null);
const errors = ref({});

const form = reactive({
  amount: null,
  name: "",
  tag_id: null,
  transactable_type: "Account",
  transactable_id: null,
});


const changeState = (to) => {
  state.value = to;
}

const getTransactionSummary = async () => {
  summary.value = await axios.post(route('transactions.per_cycle'))
    .then(res => {
      return res.data;
    });
}

const getTransactions = async () => {
  transactions.value = await axios.post(route('transactions.list'))
    .then(res => {
      return res.data;
    });
}

const getAccounts = async () => {
  accounts.value = await axios.get(route('accounts.list', {
    selection: true
  }))
    .then(res => {
      return res.data;
    });
}

const getTags = async () => {
  tags.value = await axios.post(route('tags.list'))
    .then(res => {
      return res.data;
    });
}

const createTransaction = async () => {
  await axios.post(route('transactions.create'), form)
    .then(async (res) => {
      console.log("The transaction has been created!");

      await getTransactions();
      await getTransactionSummary();
      changeState('list');
    })
    .catch(err => {
      errors.value = err.response.data.errors;
    });
};

onMounted(async () => {
  await getTransactionSummary();
  await getTransactions();
  await getAccounts();
  await getTags();
});

watch(state, async (v) => {
  if(v == 'list'){
    Object.assign(form, {
      amount: null,
      name: "",
      tag_id: null,
      transactable_type: "Account",
      transactable_id: null,
    });

    errors.value = {};
  }
});
</script>

<style scoped>
.remove-stepper[type="number"] {
  -moz-appearance: textfield;
}
</style>
