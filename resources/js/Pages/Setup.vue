<template>
  <div class="w-svw h-svh flex items-center justify-center">
    <Card class="max-w-2xl">
      <div class="flex gap-6">
        <div class="w-2/5">
          <i class="ti ti-cash-banknote mb-2 text-2xl text-heading"></i>
          <h5 class="text-xl font-semibold text-heading mb-2">Welcome to balance!</h5>
          <p class="text-sm font-medium text-body">
            Please set your details then press the <span class="italic font-bold">Finish Setup</span> button to save and proceed to your dashboard.
          </p>
        </div>
        <form @submit.prevent="submit" class="w-3/5 flex flex-col gap-4">
          <NumberInput :min="0" :max="999999999" :step="1000" title="Total Income" v-model="form.total_income" required />
          <NumberInput :min="0" :max="999999999" :step="1000" title="Planned Savings" v-model="form.to_save" required />
          <NumberInput :min="1" :max="31" title="Cycle Cutoff" v-model="form.cycle_cutoff" required />
          <!-- TODO: Add option to add unspent budget to next cutoff or not -->
          <DefaultButton fluid type="submit">Finish Setup</DefaultButton>
        </form>
      </div>
    </Card>
  </div>
</template>

<script setup>
import Card from '@/Components/Card.vue';
import NumberInput from '@/Components/Forms/NumberInput.vue';
import DefaultButton from '@/Components/Buttons/DefaultButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  errors: Object,
});

const form = useForm({
  first_run: true,
  total_income: 0,
  to_save: 0,
  cycle_cutoff: 1,
  currency: 'PHP',
  timezone: 'Asia/Manila'
});

const submit = async () => {
  form.post("/hello", {
    preserveState: true,
    onSuccess: () => {
      console.log("Successfully logged-in!");
    },
  });
};
</script>
