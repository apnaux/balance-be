<template>
  <div class="w-svw h-svh flex items-center justify-center">
    <Card class="w-[26rem] p-2">
      <template #title><p class="leading-6.5 text-2xl">Register for an<br/>account!</p></template>
      <template #content>
        <form @submit.prevent="submit" class="flex gap-4 flex-col">
          <FloatLabel variant="on">
            <InputText id="username" type="text" v-m  del="form.username" fluid :autocomplete="false" />
            <label for="username" class="text-sm">Assign your username</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Password id="password" v-model="form.password" fluid :feedback="false"/>
            <label for="username" class="text-sm">And, enter your very secure password</label>
          </FloatLabel>
          <Button label="Register now!" icon="ti ti-check" size="small" type="submit"/>
          <div class="text-sm font-medium text-body">Already have an account? <Link as="a" href="/login" class="text-primary hover:underline">Login here</Link></div>
        </form>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { InputText, FloatLabel, Password, Card, Button } from 'primevue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  errors: Object,
});

const form = useForm({
  username: "",
  password: ""
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
