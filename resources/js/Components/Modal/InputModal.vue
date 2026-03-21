<template>
  <BaseModal v-model:show="show" :title="props.title" @close="emits('close')">
    <template #body>
      <slot name="body" />
    </template>
    <template #footer>
      <div class="flex gap-2 items-center">
        <DefaultButton @click="emits('confirm')">
          Confirm
        </DefaultButton>
        <DefaultButton color="secondary" @click="() => { show = false }">
          Cancel
        </DefaultButton>
      </div>
    </template>
  </BaseModal>
</template>

<script setup>
import BaseModal from './BaseModal.vue';
import DefaultButton from '../Buttons/DefaultButton.vue';

const show = defineModel('show');
const props = defineProps(['title'])
const emits = defineEmits(['confirm', 'close']);
</script>
