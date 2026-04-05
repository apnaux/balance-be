<template>
  <nav class="fixed top-0 z-50 w-full bg-neutral-primary-soft border-b border-default">
    <!-- Side menu -->
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-start rtl:justify-end">
          <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar"
            aria-controls="top-bar-sidebar" type="button"
            class="sm:hidden text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
            </svg>
          </button>
          <Link as="a" href="/home" class="flex ms-2 md:me-24">
            <span class="self-center text-lg font-semibold whitespace-nowrap dark:text-white">balance</span>
          </Link>
        </div>
        <div class="flex items-center">
          <Dropdown :label="`@${page.props.user.username}`" dropdown-position="right">
            <ul class="px-2 py-2 text-sm text-body font-medium" aria-labelledby="dropdownInformationButton">
              <li>
                <Link as="a" href="/me" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded mb-1.5">
                  <IconUser :size="16" class="me-1.5" />
                  Account Settings
                </Link>
              </li>
              <li class="border-t border-default-medium pt-1.5">
                <a href="#"
                  class="inline-flex items-center w-full p-2 text-fg-danger hover:bg-neutral-tertiary-medium rounded">
                  <IconLogout :size="16" class="me-1.5" />
                  Sign out
                </a>
              </li>
            </ul>
          </Dropdown>
        </div>
      </div>
    </div>
  </nav>

  <!-- Top bar -->
  <aside id="top-bar-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary-soft border-e border-default">
      <a href="https://flowbite.com/" class="flex items-center ps-2.5 mb-5">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-6 me-3" alt="Flowbite Logo" />
        <span class="self-center text-lg text-heading font-semibold whitespace-nowrap">Flowbite</span>
      </a>
      <ul class="space-y-2 font-medium">
        <template v-for="route in routes">
          <Link :href="route.route"
            class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
            <component :is="route.icon" :size="20" class="transition duration-75 group-hover:text-fg-brand" />
            <!-- <i :class="`ti ${route.icon} text-xl `"></i> -->
            <span class="ms-3 text-sm">{{ route.name }}</span>
          </Link>
        </template>
        <li class="border-t border-default-medium pt-1.5">
          <a @click="showAddTransaction = true"
            class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
            <i :class="`ti ti-plus text-xl transition duration-75 group-hover:text-fg-brand`"></i>
            <span class="ms-3 text-sm">Add new transaction</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>

  <!-- Content -->
  <div class="p-4 sm:ml-64 mt-14">
    <slot></slot>
  </div>

  <!-- Add Transactions -->
  <TransactionForm v-model:show="showAddTransaction" @reload="emits('reload')"/>
</template>

<script setup>
import Dropdown from '../Dropdown.vue';
import TransactionForm from '../../Composites/Forms/Transaction.vue';

import { Link, usePage } from '@inertiajs/vue3';
import { IconLayoutDashboard, IconCashRegister, IconWallet, IconUser, IconLogout } from '@tabler/icons-vue';
import { ref } from 'vue';

const showAddTransaction = ref(false);
const page = usePage()
const emits = defineEmits(['reload']);
const routes = [
  {
    name: 'Dashboard',
    icon: IconLayoutDashboard,
    route: '/home'
  },
  {
    name: 'Transactions',
    icon: IconCashRegister,
    route: '/home'
  },
  {
    name: 'Budgets',
    icon: IconWallet,
    route: '/home'
  }
];
</script>
