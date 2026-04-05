<template>
  <AuthenticatedLayout @reload="getData()">
    <!--
      TODO:
      - Add following numerical data to dashboard
        - Daily Spend (compare if less, more, or equal than previous day spend)
        - Remaining Balance
        - Total saved based on data in user_transaction_cycles
        - Current Cutoff Date
      - Add graph data to dashboard
        - 12-month projected savings growth (Line Graph)
        - 7-day Daily Spend (Stacked, separated via account)
        - Most purchased item based on tag in current cutoff (Pie Chart)
    -->
    <div class="flex flex-col ml-4 mb-6">
      <h2 class="leading-5 text-body">Howdy,</h2>
      <h1 class="leading-7 text-heading text-3xl">{{ page.props.user.username }}</h1>
    </div>

    <div class="flex gap-3 mb-4" v-if="data">
      <Card class="grow">
        <IconCalendarRepeat :size="24" class="text-body mb-2"/>
        <p class="text-body text-sm">Current Cycle</p>
        <p class="text-heading text-2xl">{{ data.active_from }} - {{ data.active_until }}</p>
      </Card>
      <Card class="grow">
        <IconMoneybagMinus :size="24" class="text-body mb-2"/>
        <p class="text-body text-sm">Daily Spend</p>
        <p class="text-heading text-2xl">{{ data.daily_spend }}</p>
      </Card>
      <Card class="grow">
        <IconCashMove :size="24" class="text-body mb-2"/>
        <p class="text-body text-sm">Total Cycle Spend</p>
        <p class="text-heading text-2xl">{{ data.statement_balance }}</p>
      </Card>
      <Card class="grow">
        <IconMoneybag :size="24" class="text-body mb-2"/>
        <p class="text-body text-sm">Remaining Balance</p>
        <p class="text-heading text-2xl">{{ data.remaining_balance }} <span class="text-body font-base">of</span> {{ data.allocated_budget }}</p>
      </Card>
    </div>

    <div class="flex gap-3 mb-4">
      <Card class="grow h-96">
        <!-- Pie Chart -->
        <div class="flex gap-2">
          <IconChartPie4 :size="24" class="text-body"/>
          <p class="text-body">Spend per Category (Tag)</p>
        </div>
      </Card>
      <Card class="grow h-96">
        <!-- Line Chart -->
        <div class="flex gap-2">
          <IconCalendarDue :size="24" class="text-body"/>
          <p class="text-body">Credit Accounts</p>
        </div>
      </Card>
      <Card class="grow h-96">
        <!-- Bar Chart with Comparison to Previous Week -->
        <div class="flex gap-2">
          <IconChartBar :size="24" class="text-body"/>
          <p class="text-body">7-day Spending</p>
        </div>
      </Card>
    </div>

    <div class="flex flex-col gap-4 mb-4" v-if="transactions">
      <h2 class="text-body ml-2">Transactions List</h2>
      <TableContainer>
        <template #header>
          <TableCell type="th" scope="col" formatting="th">
            Transaction Date
          </TableCell>
          <TableCell type="th" scope="col" formatting="th">Label</TableCell>
          <TableCell type="th" scope="col" formatting="th">Amount</TableCell>
          <TableCell type="th" scope="col" formatting="th">Tag</TableCell>
          <TableCell type="th" scope="col" formatting="th">Account</TableCell>
          <TableCell type="th" scope="col" formatting="th"></TableCell>
        </template>
        <template #body>
          <template v-if="transactions.data.length > 0">
            <TableRow v-for="txn in transactions.data">
              <TableCell scope="row" formatting="tdHighlight">
                {{ txn.transacted_at_converted }}
              </TableCell>
              <TableCell>{{ txn.name }}</TableCell>
              <TableCell>{{ txn.formatted_amount }}</TableCell>
              <TableCell>{{ txn.tag.name }}</TableCell>
              <TableCell>{{ txn.account.name }}</TableCell>
              <TableCell>
                <div class="flex w-full justify-center items-center">
                  <OutlineButton size="xs" @click="selectedTransaction = txn" color="grey">
                    <IconDots :size="18" />
                  </OutlineButton>
                </div>
              </TableCell>
            </TableRow>
          </template>
          <template v-else>
            <TableRow>
              <TableCell scope="row" formatting="tdHighlight" colspan="6">
                No transactions...
              </TableCell>
            </TableRow>
          </template>
        </template>
      </TableContainer>
      <Pagination class="self-end mr-3" v-model="filters.page" :lastPage="transactions.last_page"
        @previous="filters.page = filters.page <= 1 ? 1 : filters.page - 1"
        @next="filters.page = transactions.last_page >= filters.page ? transactions.last_page : filters.page + 1" />
    </div>
  </AuthenticatedLayout>

  <TransactionOptions v-model="selectedTransaction" @close="selectedTransaction = null" @reload="getData()"/>
</template>

<script setup>
import AuthenticatedLayout from "@/Components/Layouts/AuthenticatedLayout.vue";
import TransactionOptions from "@/Composites/TransactionOptions.vue";
import OutlineButton from "@/Components/Buttons/OutlineButton.vue";
import TableContainer from "@/Components/Tables/TableContainer.vue";
import TableRow from "@/Components/Tables/TableRow.vue";
import TableCell from "@/Components/Tables/TableCell.vue";
import Pagination from "@/Components/Pagination.vue";
import Card from "@/Components/Card.vue";
import axios from 'axios';

import {
  IconDots,
  IconCalendarRepeat,
  IconMoneybagMinus,
  IconCashMove,
  IconMoneybag,
  IconCalendarDue,
  IconChartPie4,
  IconChartBar
} from "@tabler/icons-vue";
import { usePage } from "@inertiajs/vue3";
import { route } from 'ziggy-js';
import { onMounted, reactive, ref, watch } from "vue";

const page = usePage();
const data = ref();
const transactions = ref();
const selectedTransaction = ref();

const filters = reactive({
  page: 1,
  per_page: 5
});

const retrieveCycleInformation = async () => {
  await axios.get(route('transactions.cycle-data'))
    .then(res => {
      data.value = res.data;
    });
};

const getTransactions = async () => {
  await axios.get(route('transactions.list', filters))
    .then((res) => {
      transactions.value = res.data;
    })
};

const getData = async () => {
  await retrieveCycleInformation();
  await getTransactions();
}

watch(filters, () => {
  getTransactions();
})

onMounted(async () => {
  await getData()
})
</script>
