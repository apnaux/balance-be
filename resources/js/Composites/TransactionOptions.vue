<template>
  <InfoModal confirm-text="Close" :custom-footer="true" title="Transaction Info" v-model:show="show.main" @close="emits('close')">
    <template #body>
      <div class="flex flex-col ml-2 gap-0.5">
        <span class="flex gap-2 items-center text-body">
          <i :class="`ti ${model.tag.icon} text-xl`"></i>
          <p>{{ model.tag.name }}</p>
        </span>
        <p class="text-body text-6xl">{{ model.formatted_amount }}</p>
      </div>
      <TableContainer>
        <template #body>
          <TableRow v-for="(info, index) in information">
            <TableCell>
              {{ info.label }}
            </TableCell>
            <TableCell>
              {{ info.value }}
            </TableCell>
          </TableRow>
        </template>
      </TableContainer>
      <div class="flex gap-2">
        <OutlineButton color="gray" fluid @click="show.edit = true">
          Edit Transaction
      </OutlineButton>
      <OutlineButton color="success" fluid @click="show.post = true" v-if="model.account.type == 'credit'"">
          Post Transaction
        </OutlineButton>
        <DefaultButton color="danger" fluid @click="show.delete = true">
          Delete
        </DefaultButton>
      </div>
    </template>
  </InfoModal>

  <ConfirmModal v-model:show="show.post" title="Post Transaction">
    <template #body>
      <p class="text-body">Do you really want to post this transaction?</p>
    </template>
  </ConfirmModal>

  <ConfirmModal v-model:show="show.delete" title="Delete Transaction" @confirm="deleteTransaction"">
    <template #body>
      <p class="text-body">Do you really want to delete this transaction?</p>
    </template>
  </ConfirmModal>

  <TransactionForm v-model:show="show.edit" v-model="model" @reload="closeAndReload()"/>
</template>

<script setup>
import InfoModal from '@/Components/Modal/InfoModal.vue';
import ConfirmModal from '@/Components/Modal/ConfirmModal.vue';
import TransactionForm from './Forms/Transaction.vue';
import DefaultButton from '@/Components/Buttons/DefaultButton.vue';
import OutlineButton from '@/Components/Buttons/OutlineButton.vue';
import TableContainer from '@/Components/Tables/TableContainer.vue';
import TableRow from '@/Components/Tables/TableRow.vue';
import TableCell from '@/Components/Tables/TableCell.vue';
import axios from 'axios';

import { ref, watch } from 'vue';
import { route } from 'ziggy-js';

const emits = defineEmits(['close', 'reload']);
const model = defineModel();
const information = ref([]);
const show = ref({
  main: false,
  delete: false,
  edit: false,
  post: false
});

const postTransactionForm = ref({
  change_posted_date: false,
  change_posted_amount: false,
  posted_date: null,
  posted_amount: null,
  posted_currency: null
});

const closeAndReload = (modal = []) => {
  emits('reload');
  modal.forEach((m) => show.value[m] = false);
  model.value = null;
}

const deleteTransaction = async () => {
  await axios.delete(route('transactions.delete', { id: model.value.id })).then(res => {
    console.log(res.data.message);
    closeAndReload(['delete']);
  })
}

watch(model, (value) => {
  show.value.main = value ? true : false;
  if (value) {
    information.value = [
      { label: "Transaction Name", value: model.value.name },
      { label: "Transacted At", value: model.value.transacted_at_converted },
      { label: "Associated Account", value: model.value.account.name },
    ];
  } else {
    information.value = [];
  }
});
</script>
