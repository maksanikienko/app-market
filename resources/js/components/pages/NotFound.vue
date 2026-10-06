<template>
  <section class="flex flex-col items-center justify-center text-center gap-6 py-24 select-none">
    <p class="font-display font-light text-7xl md:text-8xl tracking-[0.2em] text-stone-300">404</p>

    <div class="space-y-2">
      <h1 class="text-2xl font-semibold tracking-tight text-stone-900">{{ t('notFound.title') }}</h1>
      <p class="text-sm text-stone-600 max-w-sm">{{ t('notFound.hint') }}</p>
    </div>

    <div class="flex flex-wrap justify-center gap-3">
      <RouterLink
        to="/"
        class="bg-stone-900 text-white hover:bg-stone-700 rounded-lg px-6 py-2.5 text-sm font-semibold transition-colors"
      >{{ t('notFound.home') }}</RouterLink>
      <RouterLink
        to="/products"
        class="text-stone-500 hover:text-stone-900 hover:bg-stone-100 rounded-lg px-6 py-2.5 text-sm font-medium transition-colors"
      >{{ t('notFound.catalog') }}</RouterLink>
    </div>
  </section>
</template>

<script setup>
import { useI18n } from '@/i18n'

const { t } = useI18n()
</script>
