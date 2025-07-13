<template>
  <div class="min-h-svh flex items-center justify-center">
    <form @submit.prevent="submit" class="flex flex-col gap-2 w-96 p-8 rounded-xl border border-nord-dark/50 dark:border-white dark:bg-nord-lightest/10">
      <i class="ti ti-mood-happy text-3xl"></i>
      <h1>Welcome to<br />balancé</h1>
      <TextInput v-model="form.username" :error="errors.username" label="Username" type="text" key="username" color="transparent" />
      <TextInput v-model="form.password" :error="errors.password" label="Password" type="password" key="password" color="transparent" />
      <Button type="submit" class="w-full">Log In</Button>
      <p class="text-sm">Don't have an account yet? <Link as="a" href="/register" class="font-ag-fett text-primary">Register!</Link></p>
    </form>
  </div>
</template>

<script setup>
import Button from '@/Components/Buttons/Button.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
  errors: Object,
});

const form = useForm({
  username: "",
  password: ""
});

const submit = async () => {
  form.post("/authenticate", {
    preserveState: true,
    onSuccess: () => {
      console.log("Successfully logged-in!");
    },
  });
};
</script>
