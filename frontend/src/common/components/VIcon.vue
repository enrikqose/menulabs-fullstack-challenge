<template>
  <svg aria-hidden="true">
    <use :href="`#icon-${icon}`" fill="currentColor"></use>
  </svg>
</template>

<script setup lang="ts">
const props = defineProps<{ icon: string }>();
</script>
