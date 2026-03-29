<template>
  <ConfirmModal :title="(model ? 'Edit' : 'Add new') + ' Transaction'" @confirm="showConfirm = true" v-model:show="show">
    <template #body>
      <TextInput for="transaction-label" title="Transaction Label" required v-model="form.name"/>
      <div class="flex gap-2 w-full">
        <NumberInput for="transaction-amount" title="Amount" fluid required v-model="form.amount"/>
        <Select for="transaction-tag" title="Tag" :options="tags" required has-placeholder v-model="form.tag_id" v-if="tags"/>
      </div>
      <Select for="account" title="Account" :options="accounts" required has-placeholder v-model="form.account_id" v-if="accounts"/>
      <Toggle v-model="showTransactionDateField" :label="'Change Transaction Date'"/>
      <DateTimePicker v-if="showTransactionDateField" v-model="form.transacted_at"/>
    </template>
  </ConfirmModal>

  <ConfirmModal v-model:show="showConfirm" @confirm="transact()" :title="'Confirm Action'">
    <template #body>
      <p class="text-body">Do you really want to {{ form.id ? 'edit' : 'add' }} this transaction?</p>
    </template>
  </ConfirmModal>
</template>

<script setup>
import Select from '@/Components/Forms/Select.vue';
import Toggle from '@/Components/Forms/Toggle.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import NumberInput from '@/Components/Forms/NumberInput.vue';
import ConfirmModal from '@/Components/Modal/ConfirmModal.vue';
import DateTimePicker from '@/Components/Forms/DateTimePicker.vue';

import { reactive, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import axios from 'axios';

const emits = defineEmits(['reload']);
const show = defineModel('show');
const model = defineModel();

const showTransactionDateField = ref(false);
const showConfirm = ref(false);
const form = reactive({
  id: null,
  name: '',
  amount: 0,
  tag_id: null,
  account_id: null,
  transacted_at: null
});

const accounts = ref();
const tags = ref();

const transact = async () => {
  await axios[model.value ? 'patch' : 'post'](route('transactions.create'), form)
    .then((res) => {
      console.log(res.data?.message)
      show.value = false;
      showConfirm.value = false;
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

const resetForm = () => {
  Object.assign(form, {
    id: null,
    name: '',
    amount: 0,
    tag_id: null,
    account_id: null,
    transacted_at: null
  });
}

watch(show, (value) => {
  if (value) {
    listAccounts();
    listTags();

    if (model.value) {
      Object.assign(form, {
        id: model.value.id,
        name: model.value.name,
        amount: model.value.amount,
        tag_id: model.value.tag_id,
        account_id: model.value.account_id,
        transacted_at: model.value.transacted_at_converted
      });
    }
  } else {
    resetForm();
  }
})

watch(showTransactionDateField, (value) => {
  if (!value) form.transacted_at = null;
})
</script>
