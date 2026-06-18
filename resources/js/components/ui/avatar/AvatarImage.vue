<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { AvatarImage as AvatarImageRoot, type AvatarImageProps } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps<AvatarImageProps & { class?: HTMLAttributes['class'] }>()
</script>

<template>
  <AvatarImageRoot :class="cn('aspect-square h-full w-full object-cover', props.class)" v-bind="props" />
</template>
