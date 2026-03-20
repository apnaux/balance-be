<template>
  <div class="w-svw h-svh flex items-center justify-center">
    <div class="w-full max-w-sm bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
      <form @submit.prevent="form.post('/authenticate')">
        <h5 class="text-xl font-semibold text-heading mb-6">Sign in to our platform</h5>
        <div class="mb-4">
          <label for="username" class="block mb-2.5 text-sm font-medium text-heading">Your username</label>
          <input type="text" id="username"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
            placeholder="balanceuser" required v-model="form.username"/>
        </div>
        <Password class="mb-6" show-toggle required v-model="form.password" />
        <DefaultButton fluid type="submit" class="mb-3">Login to your account</DefaultButton>
        <div class="text-sm font-medium text-body">Not registered? <Link as="a" href="/register" class="text-fg-brand hover:underline">Create account</Link></div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import DefaultButton from '@/Components/Buttons/DefaultButton.vue';
import Password from '@/Components/Forms/Password.vue';

const props = defineProps({
  errors: Object,
});

const form = useForm({
  username: "",
  password: ""
});
</script>
