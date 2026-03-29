<template>
  <Teleport to="body">
    <div :style="{'z-index': modalZIndex}" v-if="show" class="fixed top-0 w-svw h-svh flex flex-col justify-center items-center bg-black/25 text-typography-primary overflow-hidden">
      <div class="z-10 p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6" >
          <!-- Modal header -->
          <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
            <h3 class="text-lg font-medium text-heading">{{ title }}</h3>
            <button type="button" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
              @click="() => { show = false }">
              <IconX :size="18" />
            </button>
          </div>
          <!-- Modal body -->
          <div class="space-y-4 md:space-y-6 py-4 md:py-6" v-if="slots['footer']">
            <slot name="body"></slot>
          </div>
          <!-- Modal footer -->
          <div class="flex items-center border-t border-default space-x-4 pt-4 md:pt-5" v-if="slots['footer']">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
      <div class="absolute top-0 z-0 w-svw h-svh" @click="() => { show = false }"></div>
    </div>
  </Teleport>
</template>

<script setup>
import { IconX } from '@tabler/icons-vue'
import { useModalManager } from '@/Stores/ModalManager';
import { watch, useSlots, ref, computed } from 'vue';
import { v4 as uuidv4 } from 'uuid';

const modalManager = useModalManager();
const modalId = ref();

const slots = useSlots();
const show = defineModel('show');
const emits = defineEmits(['close'])
const props = defineProps({
  class: {
    type: String,
    default: 'w-96'
  },
  title: String
});

const modalZIndex = computed(() => {
  if (modalManager.stack.length && modalId.value) {
    return modalManager.getZIndex(modalId.value);
  }
  return 0;
})

watch(show, val => {
  const body = document.body;

  if (val) {
    // create random id for modal stack
    const id = uuidv4();
    modalId.value = id;
    modalManager.push(id);

    // prevent scroll when modal is shown
    body.style.overflow = 'hidden';
    body.style.overscrollBehavior = 'contain';
  } else {
    // remove id from modal stack
    modalId.value = null;
    modalManager.pop();
    // remove scroll prevention
    body.style.overflow = '';
    body.style.overscrollBehavior = '';
    // emit close
    emits('close');
  }
})
</script>
