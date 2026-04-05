<template>
  <div class="w-svw h-svh flex items-center justify-center">
    <Card class="w-[26rem] p-2">
      <template #title><p class="leading-6.5 text-2xl">Welcome to<br/>balance!</p></template>
      <template #subtitle>Login with your credentials.</template>
      <template #content>
        <form @submit.prevent="form.post('/authenticate')" class="flex gap-4 flex-col">
          <FloatLabel variant="on">
            <InputText id="username" type="text" v-model="form.username" fluid :autocomplete="false" />
            <label for="username" class="text-sm">Your Username</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Password id="password" v-model="form.password" fluid :feedback="false"/>
            <label for="username" class="text-sm">Your Password</label>
          </FloatLabel>
          <Button label="Login to your account" icon="ti ti-check" size="small" type="submit"/>
          <div class="text-sm font-medium text-body">Not registered? <Link as="a" href="/register" class="text-primary hover:underline">Create account</Link></div>
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
</script>
