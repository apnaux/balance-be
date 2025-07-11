<template>
  <div class="min-h-svh flex items-center justify-center">
    <form @submit.prevent="() => { }" class="flex flex-col gap-2 w-96 p-8 rounded-xl border border-white bg-nord-lightest/10">
      <i class="ti ti-mood-happy text-3xl"></i>
      <h1>Welcome to<br />balancé</h1>
      <p>Please log-in with your account</p>
      <TextInput label="Username" type="username" key="username" color="light_transparent" />
      <TextInput label="Password" type="password" key="password" color="light_transparent" />
      <Button type="submit" class="w-full">Log In</Button>
      <p class="text-sm">Don't have an account yet? <a class="font-ag-fett text-primary" href="/register">Register!</a></p>
    </form>
  </div>
</template>

<script setup>
import Button from '@/Components/Button.vue';
import TextInput from '@/Components/TextInput.vue';
</script>
