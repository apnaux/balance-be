<template>
  <nav class="fixed top-0 left-0 z-30 h-svh lg:w-80 p-4">
    <div class="border-nord-dark/25 border dark:border-0 dark:bg-nord-darker shadow-md h-full rounded-2xl flex flex-col justify-between">
      <div class="">
        <!-- User Info -->
        <div class="border-b border-nord-dark/25 dark:border-nord-light/25 flex justify-between items-center rounded-t-2xl px-5 py-4">
          <div class="flex flex-col justify-center">
            <h5 class="leading-4">Hello, {{ page.props.user.name }}</h5>
            <p class="text-sm">@{{ page.props.user.username }}</p>
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
      <Link as="button" href="/revoke" method="post" type="button" class="cursor-pointer border-t border-nord-dark/25 dark:border-nord-light/25 flex justify-between items-center rounded-b-2xl px-5 py-4">
        <p>Log out</p>
        <i class="ti ti-logout"></i>
      </Link>
    </div>
  </nav>

  <Transition>
    <div class="lg:ml-80 px-12 h-svh w-[calc(100svw-20rem)] fixed top-0 z-10 flex items-center justify-center" v-if="blurMain">
      <slot name="context" />
    </div>
  </Transition>

  <div class="lg:ml-80 px-12 pb-12 transition-[filter,scale] ease-in-out duration-500" :class="{'blur-2xl scale-95 select-none': blurMain}">
    <slot />
  </div>
</template>

<script setup>
import NavButton from '../NavButton.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, watch, Transition } from 'vue';

const page = usePage();
const props = defineProps({
  blurMain: Boolean
});

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
    name: 'Home',
    icon: 'home',
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

watch(() => props.blurMain, (v) => {
  let body = document.body;

  if (v) {
    body.style.overflow = 'hidden';
    body.style.overscrollBehavior = 'contain';
  } else {
    body.style.overflow = '';
    body.style.overscrollBehavior = '';
  }
})

onMounted(() => {
  let body = document.body;

  body.style.overflow = '';
  body.style.overscrollBehavior = '';
})
</script>

<style>
/* we will explain what these classes do next! */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease, scale 0.5s ease;
}

.v-enter-from {
  scale: 0.9;
}

.v-leave-to {
  scale: 0.9;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
