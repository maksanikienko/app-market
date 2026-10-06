<template>
  <Toaster position="top-right" rich-colors close-button :theme="isDark ? 'dark' : 'light'" />
  <router-view />
</template>

<script setup>
import { useDark } from '@vueuse/core'
import { Toaster } from '@/components/ui/sonner'

const isDark = useDark()
</script>
