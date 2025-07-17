<template>
  <AuthenticatedLayout :blur-main="state[0] != 'list'">
    <template #context>
      <!-- Transaction Details/Add -->
      <AddTransaction v-model="form" v-if="['add', 'edit'].includes(state[0])" @submit="createTransaction()" @edit="editTransaction()" @back="state.shift()"
        :errors="errors" :accounts="accounts" :tags="tags" :remaining-balance="summary.remaining_balance" />

      <!-- View Transaction Data -->
      <ViewTransaction v-if="state[0] == 'view'" :transaction="transactionData" @edit="handleTransactionEdit()" @delete="deleteTransaction()" @back="state.shift()" />
    </template>

    <div class="flex justify-center gap-24">
      <div class="sticky top-0 left-0 flex flex-col gap-4 w-96 h-fit pt-12">
        <i class="ti ti-cash text-4xl"></i>
        <h1 class="text-4xl">Your Budget</h1>
        <TotalSpend :spend="summary.statement_balance" :budget="summary.allocated_budget" :has-overspent="summary.has_overspent" v-if="summary"/>

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
        <!-- <h5>Actions</h5> -->
        <div class="list-card">
          <button @click="state.unshift('add');" type="button">
            <i :class="[`ti ti-plus`]"></i>
            <p :class="[ ]">Add a Transaction</p>
          </button>
        </div>
      </div>
      <div class="w-[28rem] pt-12">
        <h4 class="mb-2">Recent Transactions</h4>
        <div class="list-card">
          <template v-if="transactions && transactions.data.length > 0">
            <TransactionCard
              v-for="transaction in transactions.data"
              @click="handleTransactionView(transaction)"
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
import AddTransaction from "./AddTransaction.vue";
import ViewTransaction from "./ViewTransaction.vue";

import { onMounted, reactive, ref, shallowReactive, shallowRef, watch } from "vue";
import axios from "axios";

const state = ref(['list']); // ['list', 'add', 'view', 'edit']

const transactions = shallowRef(null);
const tags = shallowRef(null);
const accounts = shallowRef(null);
const summary = shallowRef(null);
const errors = ref({});
const transactionData = shallowRef(null);

const form = reactive({
  id: null,
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

const handleTransactionView = (trn = null) => {
  transactionData.value = trn;
  state.value.unshift('view');
}

const handleTransactionEdit = () => {
  state.value.unshift('edit');
  Object.assign(form, {
    id: transactionData.value.id,
    amount: transactionData.value.amount,
    name: transactionData.value.name,
    tag_id: transactionData.value.tag_id,
    transactable_type: "Account",
    transactable_id: transactionData.value.transactable_id,
  });
}

const createTransaction = async () => {
  await axios.post(route('transactions.create'), form)
    .then(async (res) => {
      console.log("The transaction has been created!");

      await getTransactions();
      await getTransactionSummary();
      state.value.shift();
    })
    .catch(err => {
      errors.value = err.response.data.errors;
    });
};

const editTransaction = async () => {
  await axios.post(route('transactions.update'), form)
    .then(async (res) => {
      console.log("The transaction has been edited!");

      await getTransactions();
      await getTransactionSummary();
      state.value = ['list'];
    })
    .catch(err => {
      errors.value = err.response.data.errors;
    });
};

const deleteTransaction = async () => {
  await axios.post(route('transactions.delete'), {
    id: transactionData.value.id
  })
    .then(async (res) => {
      console.log("The transaction has been deleted!");

      await getTransactions();
      await getTransactionSummary();
      state.value = ['list'];
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

watch(state, async (v, o) => {
  if(v == 'list'){
    Object.assign(form, {
      id: null,
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
