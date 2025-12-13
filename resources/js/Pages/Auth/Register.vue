<template>
  <div class="w-svw h-svh flex items-center justify-center">

  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

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
