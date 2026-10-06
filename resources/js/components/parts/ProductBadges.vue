<template>
  <div class="flex flex-wrap gap-1.5">
    <Badge
      v-for="badge in badges"
      :key="badge.key"
      class="rounded-full border-0 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider shadow-sm"
      :class="badge.class"
    >{{ t(badge.label) }}</Badge>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { useI18n } from '@/i18n';

const BADGES = [
  { key: 'is_new',  label: 'product.badge.new',  class: 'bg-primary text-primary-foreground' },
  { key: 'is_hit',  label: 'product.badge.hit',  class: 'bg-warning text-foreground dark:text-background' },
  { key: 'is_sale', label: 'product.badge.sale', class: 'bg-brand text-brand-foreground' },
];

const props = defineProps({ product: { type: Object, required: true } });
const { t } = useI18n();

const badges = computed(() => BADGES.filter(badge => props.product[badge.key]));
</script>
