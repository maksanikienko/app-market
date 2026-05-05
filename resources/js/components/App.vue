<template>
  <div class="bg-gray-50/50">
    <Toaster position="top-right" richColors   />
    <router-view />
  </div>
</template>

<script setup lang="ts">
import {Toaster} from '@/components/ui/sonner';
import {onMounted} from "vue";
import { useCategoryStore } from './../store/categoryStore'

const categoryStore = useCategoryStore()

onMounted(() => {
  categoryStore.load()
})
</script>