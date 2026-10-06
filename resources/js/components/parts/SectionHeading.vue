<template>
  <div class="flex items-end justify-between gap-4">
    <div class="space-y-1.5">
      <p v-if="eyebrow" class="text-xs font-medium uppercase tracking-[0.2em] text-brand">{{ eyebrow }}</p>
      <h2 class="font-display text-3xl md:text-4xl font-semibold leading-none tracking-tight">{{ title }}</h2>
    </div>
    <slot>
      <RouterLink
        v-if="to"
        :to="to"
        class="group/link inline-flex shrink-0 items-center gap-1.5 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
      >
        {{ linkLabel }}
        <ArrowRight class="size-4 transition-transform duration-300 group-hover/link:translate-x-1" />
      </RouterLink>
    </slot>
  </div>
</template>

<script setup>
import { ArrowRight } from 'lucide-vue-next';

defineProps({
  title:     { type: String, required: true },
  eyebrow:   String,
  to:        [String, Object],
  linkLabel: String,
});
</script>
