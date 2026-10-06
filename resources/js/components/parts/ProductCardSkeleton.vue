<template>
  <div class="space-y-3">
    <Skeleton class="aspect-[4/5] w-full rounded-2xl" />
    <Skeleton class="h-3 w-1/3" />
    <Skeleton class="h-4 w-4/5" />
    <Skeleton class="h-4 w-1/4" />
  </div>
</template>

<script setup>
import { Skeleton } from '@/components/ui/skeleton';
</script>
