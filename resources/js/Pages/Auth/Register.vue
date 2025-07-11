<template>
  <div class="min-h-svh flex items-center justify-center">
    <form @submit.prevent="() => { }" class="flex flex-col gap-2 w-96 p-8 rounded-xl border border-white bg-nord-lightest/10">
      <i class="ti ti-mood-plus text-3xl"></i>
      <h1>Register to<br />balancé</h1>
      <p>Enter your details here</p>
      <TextInput label="Username" type="username" key="username" color="light_transparent" />
      <TextInput label="Password" type="password" key="password" color="light_transparent" />
      <Button type="submit" class="w-full">Register</Button>
      <p class="text-sm">Already have an account? <a class="font-ag-fett text-primary" href="/login">Log in!</a></p>
    </form>
  </div>
</template>

<script setup>
import Button from '@/Components/Button.vue';
import TextInput from '@/Components/TextInput.vue';
</script>
