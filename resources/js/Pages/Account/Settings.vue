<template>
  <AuthenticatedLayout>
    <Card class="w-full">
      <div class="flex">
        <div class="flex flex-col gap-2  text-heading grow">
          <IconTag :size="20" />
          <p class="text-xl">Tags</p>
        </div>
        <div class="flex flex-col gap-2  text-body grow">
          <p class="ml-1">Pre-defined Tags</p>
          <div class="bg-neutral-primary rounded-lg border border-default w-full h-12">

          </div>
        </div>
      </div>
    </Card>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Components/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import { IconTag } from '@tabler/icons-vue';
</script>
