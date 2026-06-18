<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { AvatarRoot, type AvatarRootProps } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps<AvatarRootProps & { class?: HTMLAttributes['class'] }>()
</script>

<template>
  <AvatarRoot :class="cn('relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full', props.class)" v-bind="props">
    <slot />
  </AvatarRoot>
</template>
