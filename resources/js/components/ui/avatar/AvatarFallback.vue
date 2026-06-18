<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { AvatarFallback as AvatarFallbackRoot, type AvatarFallbackProps } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps<AvatarFallbackProps & { class?: HTMLAttributes['class'] }>()
</script>

<template>
  <AvatarFallbackRoot
    :class="cn('flex h-full w-full items-center justify-center rounded-full bg-stone-200 text-stone-700 text-xs font-semibold', props.class)"
    v-bind="props"
  >
    <slot />
  </AvatarFallbackRoot>
</template>
