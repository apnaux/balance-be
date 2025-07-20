<template>
  <form @submit.prevent="emit(form.id ? 'edit' : 'submit')" class="flex flex-col justify-center gap-2 w-96">
    <div class="top-0 left-0 flex flex-col gap-4" v-if="form.id">
      <i class="ti ti-edit text-4xl"></i>
      <h1 class="text-4xl">Edit<br />Transaction</h1>
    </div>
    <div class="top-0 left-0 flex flex-col gap-4" v-else>
      <i class="ti ti-hexagon-plus-2 text-4xl"></i>
      <h1 class="text-4xl">Add a<br />Transaction</h1>
    </div>

    <div class="flex flex-col gap-2">
      <h4>Amount</h4>
      <div>
        <div class="bg-white/25 border-nord-dark dark:border-white border text-nord-lightest rounded-3xl max-w-svh overflow-hidden">
          <div class="w-full h-24 rounded-3xl px-6 flex items-center">
            <p class="text-6xl font-bold">{{ "₱" }}</p>
            <input v-model="form.amount" type="number" placeholder="0.00" step=".01"
              class="remove-stepper h-full w-full text-6xl font-bold outline-0 ring-0 border-0 text-nord-darker dark:text-nord-lightest dark:placeholder:text-nord-lightest/50 bg-white/0">
          </div>
        </div>
        <p class="mt-2 text-sm ml-4">Your remaining spend for this cycle is {{ remainingBalance }}</p>
      </div>

      <div class="flex flex-col gap-2">
        <TextInput v-model="form.name" :error="errors.name ? errors.name[0] : ''" label="Transaction Name" type="text" key="trn_name" color="transparent" />
        <Select v-model="form.tag_id" :error="errors.tag_id ? errors.tag_id[0] : ''" :options="tags" label="Tag" key="trn_tag" color="transparent" />
      </div>
    </div>

    <div class="list-card-x mt-4">
      <button @click="emit('back')" type="button" class="w-1/2">
        <i :class="[`ti ti-chevron-left`]"></i>
        <p :class="[ ]">Go back</p>
      </button>
      <button type="submit" class="w-1/2" v-if="form.id">
        <i :class="[`ti ti-edit`]"></i>
        <p :class="[ ]">Edit</p>
      </button>
      <button type="submit" class="w-1/2" v-else>
        <i :class="[`ti ti-plus`]"></i>
        <p :class="[ ]">Create</p>
      </button>
    </div>
  </form>
</template>

<script setup>
import Select from "@/Components/Select.vue";
import TextInput from "@/Components/TextInput.vue";

const form = defineModel();
const props = defineProps({
  errors: Object,
  accounts: Array,
  tags: Array,
  remainingBalance: String
});

const emit = defineEmits(['submit', 'edit', 'back'])
</script>

<style scoped>
.remove-stepper[type="number"] {
  -moz-appearance: textfield;
}
</style>
