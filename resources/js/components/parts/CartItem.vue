<template>
  <div class="flex gap-4 border rounded-lg p-4 bg-white">
    <img :src="item.image" :alt="item.name" class="h-24 w-24 rounded-lg object-cover" />

    <div class="flex-1 space-y-2">
      <div class="flex justify-between items-start">
        <div>
          <h3 class="font-semibold">{{ localeStore.t(item.name) }}</h3>
          <p class="text-sm text-muted-foreground">{{ formatPrice(item.price) }}</p>
          <div v-if="item.size || item.color_hex" class="flex items-center gap-1.5 mt-1">
            <span
              v-if="item.color_hex"
              class="inline-block w-3.5 h-3.5 rounded-full border border-stone-200 shrink-0"
              :style="{ backgroundColor: item.color_hex }"
            />
            <span
              v-if="item.size"
              class="text-[10px] font-medium text-stone-500 border border-stone-200 rounded px-1.5 py-0.5 leading-none"
            >{{ item.size }}</span>
          </div>
        </div>
        <Button variant="ghost" size="sm" @click="$emit('remove', item.id)" class="text-destructive hover:text-destructive">
          <Trash2 class="h-4 w-4" />
        </Button>
      </div>

      <div class="flex items-center justify-between pt-2">
        <span class="text-lg font-bold">{{ formatPrice(item.price * item.quantity) }}</span>
        <div class="flex items-center border rounded">
          <Button variant="ghost" size="sm" class="px-2" :disabled="item.quantity <= 1" @click="$emit('update-quantity', item.id, item.quantity - 1)">
            <Minus class="h-4 w-4" />
          </Button>
          <span class="w-8 text-center text-sm">{{ item.quantity }}</span>
          <Button variant="ghost" size="sm" class="px-2" :disabled="item.quantity >= MAX_CART_QUANTITY" @click="$emit('update-quantity', item.id, item.quantity + 1)">
            <Plus class="h-4 w-4" />
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import { Trash2, Minus, Plus } from 'lucide-vue-next';
import { useLocaleStore } from '@/store/localeStore.js';
import { formatPrice } from '@/lib/format.js';
import { MAX_CART_QUANTITY } from '@/config/cart.js';

defineProps({ item: { type: Object, required: true } });
defineEmits(['remove', 'update-quantity']);

const localeStore = useLocaleStore();
</script>
