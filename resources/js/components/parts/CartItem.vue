<template>
  <Card class="group flex gap-4 rounded-2xl p-3 sm:p-4 shadow-none transition-shadow duration-300 hover:shadow-[0_12px_32px_-16px_rgb(60_40_20/0.25)]">
    <div class="size-24 sm:size-28 shrink-0 overflow-hidden rounded-xl bg-muted">
      <img :src="item.image" :alt="localeStore.t(item.name)" class="size-full object-cover transition-transform duration-700 ease-out-expo group-hover:scale-105" />
    </div>

    <div class="flex min-w-0 flex-1 flex-col">
      <div class="flex items-start justify-between gap-2">
        <div class="min-w-0 space-y-1.5">
          <h3 class="line-clamp-2 font-medium leading-snug">{{ localeStore.t(item.name) }}</h3>
          <div class="flex flex-wrap items-center gap-1.5 text-xs text-muted-foreground">
            <span>{{ formatPrice(item.price) }}</span>
            <span v-if="item.color_hex" class="size-3.5 rounded-full ring-1 ring-black/10" :style="{ backgroundColor: item.color_hex }" />
            <Badge v-if="item.size" variant="outline" class="h-5 rounded-md px-1.5 text-[10px]">{{ item.size }}</Badge>
          </div>
        </div>
        <Button variant="ghost" size="icon" class="size-8 shrink-0 rounded-full text-muted-foreground hover:bg-destructive/10 hover:text-destructive" @click="$emit('remove', item.id)">
          <Trash2 />
        </Button>
      </div>

      <div class="mt-auto flex items-center justify-between gap-2 pt-3">
        <QuantityStepper
          :model-value="item.quantity"
          :max="MAX_CART_QUANTITY"
          @update:model-value="$emit('update-quantity', item.id, $event)"
        />
        <span class="text-base sm:text-lg font-semibold tabular-nums">{{ formatPrice(item.price * item.quantity) }}</span>
      </div>
    </div>
  </Card>
</template>

<script setup>
import { Trash2 } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import QuantityStepper from '@/components/parts/QuantityStepper.vue';
import { useLocaleStore } from '@/store/localeStore.js';
import { formatPrice } from '@/lib/format.js';
import { MAX_CART_QUANTITY } from '@/config/cart.js';

defineProps({ item: { type: Object, required: true } });
defineEmits(['remove', 'update-quantity']);

const localeStore = useLocaleStore();
</script>
