<template>
  <nav class="fixed top-0 left-0 z-30 h-svh lg:w-80 p-4">
    <div class="bg-nord-darker shadow-md h-full rounded-2xl flex flex-col justify-between">
      <div class="">
        <!-- User Info -->
        <div class="border-b border-nord-light/25 flex justify-between items-center rounded-t-2xl px-5 py-4">
          <div class="flex flex-col justify-center">
            <h5 class="leading-4">Hello, {{ page.props.user.name }}</h5>
            <p class="text-sm">User</p>
          </div>
        </div>

        <!-- Navigation | border-b border-nord-light/25 border-dashed -->
        <div class="m-4 pb-4 flex flex-col">
          <NavButton v-for="route in routes" :icon="route.icon" :name="route.name" :href="route.href" />
        </div>

        <!-- Admin Settings -->
        <!-- <div class="mx-4 mb-4 pb-4 flex flex-col">
          <NavButton v-for="nav in sample_admin_nav" :icon="nav.icon" :name="nav.name" />
        </div> -->
      </div>

      <!-- Logout -->
      <Link as="button" href="/revoke" method="post" type="button" class="cursor-pointer border-t border-nord-light/25 flex justify-between items-center rounded-b-2xl px-5 py-4">
        <p>Log out</p>
        <i class="ti ti-logout"></i>
      </Link>
    </div>
  </nav>
  <div class="lg:ml-80 px-12 pb-12">
    <slot />
  </div>
</template>

<script setup>
import NavButton from '../NavButton.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const sample_admin_nav = [
  {
    name: 'User Management',
    icon: 'users'
  },
  {
    name: 'Site Configuration',
    icon: 'world-cog'
  },
]

const routes = [
  {
    name: 'Budgets',
    icon: 'cash',
    href: 'budgets.index'
  },
  {
    name: 'Accounts',
    icon: 'wallet',
    href: 'accounts.index'
  },
  // {
  //   name: 'Targeted Budgets',
  //   icon: 'paperclip'
  // },
  // {
  //   name: 'Settings',
  //   icon: 'settings'
  // },
]
</script>
