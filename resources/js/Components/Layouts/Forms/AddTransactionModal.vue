<template>
  <InputModal title="Add new Transaction" @confirm="addTransaction()" v-model:show="show">
    <template #body>
      <TextInput for="transaction-label" title="Transaction Label" required v-model="form.name"/>
      <div class="flex gap-2 w-full">
        <NumberInput for="transaction-amount" title="Amount" fluid required v-model="form.amount"/>
        <Select for="transaction-tag" title="Tag" :options="tags" required has-placeholder v-model="form.tag_id" v-if="tags"/>
      </div>
      <Select for="account" title="Account" :options="accounts" required has-placeholder v-model="form.account_id" v-if="accounts"/>
    </template>
  </InputModal>
</template>

<script setup>
import TextInput from '@/Components/Forms/TextInput.vue';
import NumberInput from '@/Components/Forms/NumberInput.vue';
import Select from '@/Components/Forms/Select.vue';
import InputModal from '@/Components/Modal/InputModal.vue';
import { reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import axios from 'axios';

const show = defineModel('show')
const emits = defineEmits(['reload']);
const form = reactive({
  name: '',
  amount: 0,
  tag_id: null,
  account_id: null,
  transacted_at: null
});

const accounts = ref();
const tags = ref();

const addTransaction = async () => {
  const conf = confirm('Are you sure you want to add this transaction?')
  if (!conf) return;

  await axios.post(route('transactions.create'), form)
    .then((res) => {
      console.log(res.data?.message)
      show.value = false;
      emits('reload');
    })
    .finally(() => {

    })
}

const listAccounts = async () => {
  await axios.get(route('accounts.list', { selection: true }))
    .then((res) => {
      accounts.value = res.data;
    })
}

const listTags = async () => {
  await axios.get(route('tags.list'))
    .then((res) => {
      tags.value = res.data;
    })
}

watch(show, (value) => {
  if (value) {
    listAccounts();
    listTags();
  } else {
    Object.assign(form, {
      name: '',
      amount: 0,
      tag_id: null,
      account_id: null,
      transacted_at: null
    })
  }
})
</script>
