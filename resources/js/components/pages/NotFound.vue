<template>
  <section class="relative flex flex-col items-center justify-center gap-6 overflow-hidden py-24 text-center select-none">
    <div class="pointer-events-none absolute top-10 size-72 rounded-full bg-brand/20 blur-3xl" />
    <p class="animate-rise relative font-display text-[9rem] md:text-[12rem] font-semibold italic leading-none tracking-tight text-foreground/10">404</p>

    <div class="animate-rise stagger relative -mt-16 space-y-2" style="--i: 1">
      <h1 class="font-display text-3xl md:text-4xl font-semibold tracking-tight">{{ t('notFound.title') }}</h1>
      <p class="max-w-sm text-muted-foreground">{{ t('notFound.hint') }}</p>
    </div>

    <div class="animate-rise stagger relative flex flex-wrap justify-center gap-3" style="--i: 2">
      <Button size="lg" class="rounded-full" as-child>
        <RouterLink :to="{ name: 'home' }"><House /> {{ t('notFound.home') }}</RouterLink>
      </Button>
      <Button size="lg" variant="outline" class="rounded-full" as-child>
        <RouterLink :to="{ name: 'products' }">{{ t('notFound.catalog') }} <ArrowRight /></RouterLink>
      </Button>
    </div>
  </section>
</template>

<script setup>
import { House, ArrowRight } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { useI18n } from '@/i18n'

const { t } = useI18n()
</script>
