<template>
  <Card class="group overflow-hidden transition-all hover:shadow-md">
    <div class="relative aspect-square overflow-hidden bg-muted">
      <img
          v-if="product.image"
          :src="`/storage/${product.image}`"
          :alt="product.name"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-muted-foreground">
        <Package class="h-10 w-10" />
      </div>
      <Badge v-if="product.isNew" class="absolute top-2 right-2">New</Badge>
    </div>

    <CardContent class="p-4 space-y-3">
      <div>
        <p class="text-xs text-muted-foreground uppercase">{{ product.category?.name }}</p>
        <h3 class="font-semibold line-clamp-2">{{ product.name }}</h3>
      </div>

      <div class="flex items-baseline gap-2">
        <span class="text-lg font-bold">${{ Number(product.price).toFixed(2) }}</span>
        <span v-if="product.originalPrice" class="text-sm line-through text-muted-foreground">
          ${{ Number(product.originalPrice).toFixed(2) }}
        </span>
      </div>

      <div class="flex gap-2 pt-2">
        <Button
            variant="outline"
            size="sm"
            class="flex-1 cursor-pointer"
            @click="$router.push(`/product/${product.id}`)"
        >
          View
        </Button>
        <Button
            size="sm"
            class="flex-1 cursor-pointer"
            :variant="inCart ? 'secondary' : 'default'"
            :disabled="inCart"
            @click="handleAdd"
        >
          <Check v-if="inCart" class="h-4 w-4 mr-1" />
          <ShoppingCart v-else class="h-4 w-4 mr-1" />
          {{ inCart ? 'Added' : 'Add' }}
        </Button>
      </div>
    </CardContent>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ShoppingCart, Check, Package } from 'lucide-vue-next';
import { useCartStore } from '@/store/cartStore.js';

const props = defineProps({
  product: { type: Object, required: true }
});

const emit = defineEmits(['add-to-cart']);

const cartStore = useCartStore();
const inCart = computed(() => cartStore.itemIds.has(props.product.id));

const handleAdd = () => {
  if (!inCart.value) emit('add-to-cart', props.product);
};
</script>
