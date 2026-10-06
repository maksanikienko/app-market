<template>
  <Button variant="ghost" size="icon" class="relative rounded-full" :aria-label="t('nav.theme')" @click="isDark = !isDark">
    <Sun class="absolute transition-all duration-500 ease-spring" :class="isDark ? 'rotate-90 scale-0 opacity-0' : 'rotate-0 scale-100'" />
    <Moon class="absolute transition-all duration-500 ease-spring" :class="isDark ? 'rotate-0 scale-100' : '-rotate-90 scale-0 opacity-0'" />
  </Button>
</template>

<script setup>
import { useDark } from '@vueuse/core';
import { Sun, Moon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useI18n } from '@/i18n';

const isDark = useDark();
const { t }  = useI18n();
</script>
