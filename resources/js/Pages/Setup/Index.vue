<template>
  <div class="min-h-svh flex gap-36 items-center justify-center">
    <div class="flex flex-col gap-4 w-96">
      <i class="ti ti-settings-exclamation text-4xl"></i>
      <h1 class="text-4xl">Time to<br />set things up!</h1>
      <p class="">
        Thank you for choosing balancé for your transaction tracking needs!
        After this setup, you may now start tracking your transactions freely.
      </p>
    </div>
    <form @submit.prevent="submit" class="flex flex-col gap-2 w-[24rem]">
      <TextInput v-model="form.name" :error="errors.name" label="Your Name"
        placeholder="John Doe" type="username" key="username" color="light_transparent" />
      <div class="flex gap-2 border-t dark:border-nord-lightest/50 border-dashed pt-4 mt-4">
        <TextInput v-model="form.allocated_budget" :error="errors.allocated_budget" label="Allocated Budget"
          placeholder="10000" type="number" key="budget" color="light_transparent" />
        <TextInput v-model="form.cycle_cutoff" :error="errors.cycle_cutoff" label="Cycle Cutoff"
          placeholder="12" type="number" key="cycle_cutoff" color="light_transparent" />
      </div>
      <div class="flex gap-2 border-b dark:border-nord-lightest/50 border-dashed pb-6 mb-2">
        <TextInput v-model="form.currency" :error="errors.currency" label="Preferred Currency"
          placeholder="USD" type="text" key="currency" color="light_transparent" />
        <TextInput v-model="form.timezone" :error="errors.timezone" label="Your Timezone"
          placeholder="America/New York" type="text" key="timezone" color="light_transparent" />
      </div>

      <OutlineButton type="submit" class="w-full">
        <span class="font-ag-fett">I'm Finished!</span>
      </OutlineButton>
    </form>
  </div>
</template>

<script setup>
import TextInput from '@/Components/TextInput.vue';
import OutlineButton from '@/Components/Buttons/OutlineButton.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
  errors: Object,
});

const form = useForm({
  first_run: true,
  name: "",
  allocated_budget: 0,
  cycle_cutoff: 1,
  currency: 'PHP',
  timezone: 'Asia/Manila'
});

const submit = () => {
  form.post('/user/update', {
    preserveState: true,
    onSuccess: () => {
      console.log("Successfully updated user configuration!");
    },
  })
}

onMounted(() => {
  if(props.errors.error){
    // TODO: Add Toast
    console.log(props.errors.error);
  }
});
</script>
