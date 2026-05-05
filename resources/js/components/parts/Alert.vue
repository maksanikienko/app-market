<template>
  <div :class="classes" class="mb-4 p-4 rounded">
    {{ message }}
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { defineProps } from 'vue';

const props = defineProps({
  type: String,
  message: String
});

const classes = computed(() => {
  return props.type === 'success' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800';
});
</script>