<template>
  <div v-if="loading" class="space-y-8">
    <Skeleton class="h-5 w-48" />
    <div class="grid gap-8 lg:grid-cols-2">
      <Skeleton class="aspect-square rounded-lg" />
      <div class="space-y-4">
        <Skeleton class="h-8 w-3/4" />
        <Skeleton class="h-5 w-1/2" />
        <Skeleton class="h-24 w-full" />
        <Skeleton class="h-12 w-full" />
      </div>
    </div>
  </div>

  <div v-else-if="product" class="space-y-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm">
      <RouterLink to="/products" class="text-muted-foreground hover:text-foreground transition-colors">
        Products
      </RouterLink>
      <span class="text-muted-foreground">/</span>
      <span>{{ product.name }}</span>
    </div>

    <div class="grid gap-8 lg:grid-cols-2">
      <!-- Image -->
      <div class="space-y-4">
        <div class="aspect-square overflow-hidden rounded-lg bg-muted">
          <img
              v-if="product.image"
              :src="`/storage/${product.image}`"
              :alt="product.name"
              class="h-full w-full object-cover"
          />
          <div v-else class="h-full w-full flex items-center justify-center text-muted-foreground">
            <Package class="h-16 w-16" />
          </div>
        </div>
      </div>

      <!-- Details -->
      <div class="space-y-6">
        <div>
          <p class="text-sm text-muted-foreground uppercase tracking-wider">
            {{ product.category?.name }}
          </p>
          <h1 class="text-3xl font-bold mt-1">{{ product.name }}</h1>
        </div>

        <!-- Price -->
        <div class="border-y py-4">
          <span class="text-3xl font-bold">${{ Number(product.price).toFixed(2) }}</span>
        </div>

        <!-- Description -->
        <p v-if="product.description" class="text-muted-foreground leading-relaxed">
          {{ product.description }}
        </p>

        <!-- Quantity -->
        <div class="flex items-center gap-4">
          <span class="text-sm font-medium">Quantity:</span>
          <div class="flex items-center border rounded-lg">
            <Button variant="ghost" size="sm" class="px-3" @click="quantity = Math.max(1, quantity - 1)">
              <Minus class="h-4 w-4" />
            </Button>
            <span class="w-12 text-center text-sm font-medium">{{ quantity }}</span>
            <Button variant="ghost" size="sm" class="px-3" @click="quantity++">
              <Plus class="h-4 w-4" />
            </Button>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-2">
          <Button
              size="lg"
              class="flex-1"
              @click="addToCart"
              :disabled="adding || isInCart"
              :variant="isInCart ? 'secondary' : 'default'"
          >
            <Check v-if="isInCart" class="h-5 w-5 mr-2" />
            <ShoppingCart v-else class="h-5 w-5 mr-2" />
            {{ adding ? 'Adding...' : isInCart ? 'Added' : 'Add to cart' }}
          </Button>
          <Button variant="outline" size="lg" class="px-6">
            <Heart class="h-5 w-5" />
          </Button>
        </div>

        <!-- Meta -->
        <div class="space-y-2 border-t pt-4 text-sm">
          <div class="flex justify-between">
            <span class="text-muted-foreground">Article:</span>
            <span class="font-medium">{{ product.code }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-muted-foreground">Delivery:</span>
            <span class="font-medium">2–3 days</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Not found -->
  <div v-else class="flex h-64 items-center justify-center rounded-lg border border-dashed">
    <div class="text-center space-y-2">
      <Package class="h-12 w-12 mx-auto text-muted-foreground" />
      <p class="font-medium">Product not found</p>
      <RouterLink to="/products">
        <Button variant="outline" size="sm">Back to products</Button>
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import { Heart, Minus, Plus, ShoppingCart, Package, Check } from 'lucide-vue-next';
import { useProductService } from '@/services/productService.js';
import { useCartStore } from '@/store/cartStore.js';

const route     = useRoute();
const { getById } = useProductService();
const cartStore = useCartStore();

const product  = ref(null);
const loading  = ref(true);
const adding   = ref(false);
const quantity = ref(1);

const isInCart = computed(() => product.value && cartStore.itemIds.has(product.value.id));

onMounted(async () => {
  try {
    product.value = await getById(route.params.id);
  } catch (e) {
    console.error('Failed to fetch product:', e);
  } finally {
    loading.value = false;
  }
});

const addToCart = async () => {
  if (!product.value || adding.value || isInCart.value) return;
  adding.value = true;
  try {
    // Add quantity times — cartStore.add updates items after each call
    for (let i = 0; i < quantity.value; i++) {
      await cartStore.add(product.value.id);
    }
    quantity.value = 1;
  } catch (e) {
    console.error('Failed to add to cart:', e);
  } finally {
    adding.value = false;
  }
};
</script>
