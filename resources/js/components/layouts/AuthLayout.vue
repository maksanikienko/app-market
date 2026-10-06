<template>
  <div class="grid min-h-svh bg-background lg:grid-cols-2">
    <aside class="relative hidden overflow-hidden lg:block">
      <img :src="HERO_IMAGE" alt="" class="animate-ken-burns absolute inset-0 size-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent" />
      <RouterLink :to="{ name: 'home' }" class="absolute left-10 top-10 text-sm font-semibold tracking-[0.3em] text-white">FORYOU</RouterLink>
      <div class="absolute inset-x-10 bottom-10 max-w-md space-y-3 text-white">
        <p class="animate-rise font-display text-5xl font-semibold leading-[0.95]">{{ t('home.banner.title') }}</p>
        <p class="animate-rise stagger text-white/70" style="--i: 1">{{ t('home.store.tagline') }}</p>
      </div>
    </aside>

    <main class="flex items-center justify-center px-4 py-10 sm:px-10">
      <RouterView v-slot="{ Component, route }">
        <Transition name="page" mode="out-in">
          <component :is="Component" :key="route.path" />
        </Transition>
      </RouterView>
    </main>
  </div>
</template>

<script setup>
import { useI18n } from '@/i18n'

const HERO_IMAGE = '/storage/home/main-image.png'

const { t } = useI18n()
</script>
