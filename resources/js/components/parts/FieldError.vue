<template>
  <p v-if="message" class="text-xs text-destructive mt-1">{{ message }}</p>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ msgs: { type: [Array, String], default: null } })

const message = computed(() => (Array.isArray(props.msgs) ? props.msgs[0] : props.msgs))
</script>
