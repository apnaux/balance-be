<template>
  <div class="w-svw h-svh flex items-center justify-center">
    <Card class="max-w-2xl">
      <template #content>
        <div class="flex gap-6">
          <div class="w-2/5">
            <i class="ti ti-cash-banknote mb-2 text-2xl text-heading"></i>
            <h5 class="text-xl font-semibold text-heading mb-2">Welcome to balance!</h5>
            <p class="text-sm font-medium text-body">
              Please set your details then press the <span class="italic font-bold">Finish Setup</span> button to save and proceed to your dashboard.
            </p>
          </div>
          <form @submit.prevent="submit" class="w-3/5 flex flex-col gap-4">
            <FloatLabel variant="on">
              <InputNumber v-model="form.total_income" inputId="total-income" mode="currency" currency="PHP" locale="en-PH" fluid />
              <label for="total-income">Total Income</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputNumber v-model="form.to_save" inputId="to-save" mode="currency" currency="PHP" locale="en-PH" fluid />
              <label for="to-save">Planned Savings</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputNumber v-model="form.cycle_cutoff" :min="1" :max="31" inputId="cycle-cutoff" fluid />
              <label for="cycle-cutoff">Cycle Cutoff</label>
            </FloatLabel>
            <Button type="submit" label="Finish Setup" />
          </form>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { InputNumber, FloatLabel, Card, Button } from 'primevue';
// import Card from '@/Components/Card.vue';
// import NumberInput from '@/Components/Forms/NumberInput.vue';
// import DefaultButton from '@/Components/Buttons/DefaultButton.vue';
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
    // preserveState: true,
    onSuccess: () => {
      console.log("Successfully logged-in!");
    },
  });
};
</script>
