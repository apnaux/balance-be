<template>
  <BaseModal v-model:show="show" :title="props.title" @close="emits('close')">
    <template #body>
      <slot name="body" />
    </template>
    <template #footer>
      <div class="flex gap-2 items-center">
        <template v-if="!customFooter">
          <DefaultButton color="secondary" @click="() => { show = false }">
            {{ confirmText ?? 'Close' }}
          </DefaultButton>
        </template>
        <template v-else>
          <slot name="footer" />
        </template>
      </div>
    </template>
  </BaseModal>
</template>

<script setup>
import BaseModal from './BaseModal.vue';
import DefaultButton from '../Buttons/DefaultButton.vue';

const show = defineModel('show');
const props = defineProps(['title', 'confirmText', 'customFooter'])
const emits = defineEmits(['close']);
</script>
