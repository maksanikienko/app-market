<template>
  <div class="bg-gray-50/50">
    <Toaster position="top-right" rich-colors />
    <router-view />
  </div>
</template>

<script setup>
import { Toaster } from '@/components/ui/sonner'
</script>
