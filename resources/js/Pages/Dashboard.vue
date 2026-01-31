<template>
  <AuthenticatedLayout>
    <!--
      TODO:
      - Add following numerical data to dashboard
        - Daily Spend (compare if less, more, or equal than previous day spend)
        - Remaining Balance
        - Total saved based on data in user_transaction_cycles
        - Current Cutoff Date
      - Add graph data to dashboard
        - 12-month projected savings growth (Line Graph)
        - 7-day Daily Spend (Stacked, separated via account)
        - Most purchased item based on tag in current cutoff (Pie Chart)
    -->
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Components/Layouts/AuthenticatedLayout.vue';
</script>
