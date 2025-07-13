<template>
  <div class="min-h-svh flex items-center justify-center">
    <form @submit.prevent="submit" class="flex flex-col gap-2 w-96 p-8 rounded-xl border border-nord-dark/50 dark:border-white dark:bg-nord-lightest/10">
      <i class="ti ti-mood-plus text-3xl"></i>
      <h1>Register to<br />balancé</h1>
      <TextInput v-model="form.username" :error="errors.username" label="Username" type="text" key="username" color="transparent" />
      <TextInput v-model="form.password" :error="errors.password" label="Password" type="password" key="password" color="transparent" />
      <TextInput v-model="form.password_confirmation" :error="errors.password_confirmation" label="Confirm Password" type="password" key="confirm-password" color="transparent" />
      <Button type="submit" class="w-full">Register</Button>
      <p class="text-sm">Already have an account? <Link as="a" href="/login" class="font-ag-fett text-primary">Log in!</Link></p>
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
  password: "",
  password_confirmation: ""
});

const submit = async () => {
  form.post("/register/create", {
    preserveState: true,
    onSuccess: () => {
      console.log("Successfully logged-in!");
    },
  });
};
</script>
