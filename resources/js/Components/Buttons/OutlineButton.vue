<template>
  <BaseButton
    :fluid="fluid" :size="size"
    :extra="[
      'border focus:ring-4',
      pill ? 'rounded-full' : 'rounded-base',
      colors[color],
    ]">
    <slot />
  </BaseButton>
</template>

<script setup>
import BaseButton from './BaseButton.vue';
defineProps({
  fluid: Boolean,
  pill: Boolean,
  size: {
    type: String,
    default: 'base'
  },
  color: {
    type: String,
    default: 'default'
  }
});

const colors = {
  default: 'text-fg-brand bg-neutral-primary border-brand hover:bg-brand hover:text-white focus:ring-brand-subtle',
  gray: 'text-body bg-neutral-primary border-default hover:bg-neutral-secondary-soft hover:text-heading focus:ring-neutral-tertiary',
  success: 'text-success bg-neutral-primary border-success hover:bg-success hover:text-white focus:ring-neutral-tertiary',
  danger: 'text-danger bg-neutral-primary border-danger hover:bg-danger hover:text-white focus:ring-neutral-tertiary',
  warning: 'text-warning bg-neutral-primary border-warning hover:bg-warning hover:text-white focus:ring-neutral-tertiary',
}
</script>
