<template>
  <BaseButton
    :fluid="fluid" :size="size"
    :extra="[
      'border focus:ring-4',
      pill ? 'rounded-full' : 'rounded-base',
      {'shadow-sm': color !== 'ghost'},
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
  default: 'text-white bg-brand box-border border-transparent hover:bg-brand-strong focus:ring-brand-medium',
  secondary: 'text-body bg-neutral-secondary-medium box-border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-neutral-tertiary',
  tertiary: 'text-body bg-neutral-primary-soft border-default hover:bg-neutral-secondary-medium hover:text-heading focus:ring-neutral-tertiary-soft',
  success: 'text-white bg-success box-border border-transparent hover:bg-success-strong focus:ring-success-medium',
  danger: 'text-white bg-danger box-border border-transparent hover:bg-danger-strong focus:ring-danger-medium',
  warning: 'text-white bg-warning box-border border-transparent hover:bg-warning-strong focus:ring-warning-medium',
  dark: 'text-white bg-dark box-border border-transparent hover:bg-dark-strong focus:ring-neutral-tertiary',
  ghost: 'text-heading bg-transparent box-border border-transparent hover:bg-neutral-secondary-medium focus:ring-neutral-tertiary'
}
</script>
