<template>
  <div class="space-y-10 pb-8">
    <section class="animate-rise space-y-3">
      <h1 class="font-display text-4xl md:text-5xl font-semibold leading-none tracking-tight">{{ t('contact.title') }}</h1>
      <p class="max-w-lg text-muted-foreground">{{ t('contact.subtitle') }}</p>
    </section>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2 lg:gap-10">
      <div class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Card
            v-for="(card, i) in cards"
            :key="card.label"
            class="animate-rise stagger group gap-0 rounded-2xl p-5 shadow-none transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_32px_-16px_rgb(60_40_20/0.25)]"
            :style="{ '--i': i + 1 }"
          >
            <span class="mb-4 grid size-10 place-items-center rounded-xl bg-brand/10 text-brand transition-transform duration-500 ease-spring group-hover:scale-110 group-hover:rotate-[-6deg]">
              <component :is="card.icon" class="size-5" />
            </span>
            <p class="text-xs font-medium uppercase tracking-[0.15em] text-muted-foreground">{{ t(card.label) }}</p>
            <a v-if="card.href" :href="card.href" class="mt-1.5 block break-all font-medium transition-colors hover:text-brand">{{ t(card.value) }}</a>
            <div v-else class="mt-1.5 space-y-0.5 font-medium">
              <p v-for="line in card.lines" :key="line">{{ t(line) }}</p>
            </div>
          </Card>
        </div>

        <Card class="animate-rise stagger gap-0 rounded-2xl p-5 shadow-none" style="--i: 5">
          <h2 class="font-display text-xl font-semibold">{{ t('contact.directions.title') }}</h2>
          <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ t('contact.directions.desc') }}</p>
        </Card>
      </div>

      <div class="animate-rise stagger relative h-[420px] overflow-hidden rounded-3xl border bg-muted lg:h-[540px]" style="--i: 3">
        <div ref="mapEl" class="size-full" />
        <div v-if="error" class="absolute inset-0 flex flex-col items-center justify-center gap-3">
          <MapPin class="size-8 text-muted-foreground/50" />
          <p class="px-6 text-center text-sm text-muted-foreground">{{ t('contact.map.noKey') }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { MapPin, Phone, Mail, Clock } from 'lucide-vue-next'
import { Card } from '@/components/ui/card'
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

const cards = [
  { icon: MapPin, label: 'contact.address.label', lines: ['contact.address.value'] },
  { icon: Phone,  label: 'contact.phone.label',   value: 'contact.phone.value', href: `tel:${phoneRaw}` },
  { icon: Mail,   label: 'contact.email.label',   value: 'contact.email.value', href: `mailto:${emailRaw}` },
  { icon: Clock,  label: 'contact.hours.label',   lines: ['contact.hours.weekdays', 'contact.hours.weekend'] },
]
</script>
