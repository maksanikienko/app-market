<template>
  <div class="space-y-12 pb-16">

    <!-- Page header -->
    <section class="space-y-2">
      <h1 class="text-3xl font-semibold tracking-tight text-stone-900">{{ t('contact.title') }}</h1>
      <p class="text-stone-500 text-sm max-w-lg">{{ t('contact.subtitle') }}</p>
    </section>

    <!-- Main content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

      <!-- Left: contact blocks -->
      <div class="space-y-6">

        <!-- Info cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="rounded-xl border border-stone-200 bg-white p-5 space-y-2">
            <div class="flex items-center gap-2 text-stone-900">
              <MapPin class="h-4 w-4 shrink-0" />
              <span class="text-sm font-semibold">{{ t('contact.address.label') }}</span>
            </div>
            <p class="text-sm text-stone-600 leading-relaxed">{{ t('contact.address.value') }}</p>
          </div>

          <div class="rounded-xl border border-stone-200 bg-white p-5 space-y-2">
            <div class="flex items-center gap-2 text-stone-900">
              <Phone class="h-4 w-4 shrink-0" />
              <span class="text-sm font-semibold">{{ t('contact.phone.label') }}</span>
            </div>
            <a :href="`tel:${phoneRaw}`" class="text-sm text-stone-600 hover:text-stone-900 transition-colors">
              {{ t('contact.phone.value') }}
            </a>
          </div>

          <div class="rounded-xl border border-stone-200 bg-white p-5 space-y-2">
            <div class="flex items-center gap-2 text-stone-900">
              <Mail class="h-4 w-4 shrink-0" />
              <span class="text-sm font-semibold">{{ t('contact.email.label') }}</span>
            </div>
            <a :href="`mailto:${emailRaw}`" class="text-sm text-stone-600 hover:text-stone-900 transition-colors break-all">
              {{ t('contact.email.value') }}
            </a>
          </div>

          <div class="rounded-xl border border-stone-200 bg-white p-5 space-y-2">
            <div class="flex items-center gap-2 text-stone-900">
              <Clock class="h-4 w-4 shrink-0" />
              <span class="text-sm font-semibold">{{ t('contact.hours.label') }}</span>
            </div>
            <div class="text-sm text-stone-600 space-y-0.5">
              <p>{{ t('contact.hours.weekdays') }}</p>
              <p>{{ t('contact.hours.weekend') }}</p>
            </div>
          </div>
        </div>

        <!-- How to find us -->
        <div class="rounded-xl border border-stone-200 bg-white p-5 space-y-3">
          <h2 class="text-sm font-semibold text-stone-900">{{ t('contact.directions.title') }}</h2>
          <p class="text-sm text-stone-600 leading-relaxed">{{ t('contact.directions.desc') }}</p>
        </div>

      </div>

      <!-- Right: map -->
      <div class="rounded-xl overflow-hidden border border-stone-200 bg-stone-100 h-[420px] lg:h-[520px] relative">
        <!-- Map container -->
        <div ref="mapEl" class="w-full h-full" />

        <!-- Shown while key is missing -->
        <div
          v-if="error"
          class="absolute inset-0 flex flex-col items-center justify-center gap-3 bg-stone-100"
        >
          <MapPin class="h-8 w-8 text-stone-300" />
          <p class="text-sm text-stone-400 text-center px-6">
            {{ t('contact.map.noKey') }}
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { MapPin, Phone, Mail, Clock } from 'lucide-vue-next'
import { useI18n } from '@/i18n'
import { useGoogleMap } from '@/composables/useGoogleMap.js'

const { t } = useI18n()

// ── Store coordinates ──────────────────────────────────────────────────────
const CENTER = { lat: 46.99130849144529, lng: 28.859336123481828 }

const { mapEl, error } = useGoogleMap({
  center: CENTER,
  zoom:   16,
  title:  'ForYou',
})

// Raw values for href attributes
const phoneRaw = '+37300000000'
const emailRaw = 'contact@foryou.md'
</script>
