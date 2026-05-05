<template>
  <div class="space-y-16 pb-16">

    <!-- Hero Section -->
    <section class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-slate-900 to-slate-700 text-white px-8 py-20 flex flex-col items-center text-center gap-6">
      <span class="text-sm font-medium bg-white/10 border border-white/20 rounded-full px-4 py-1 uppercase tracking-widest">
        New arrivals 2025
      </span>
      <h1 class="text-4xl md:text-6xl font-bold leading-tight max-w-3xl">
        Discover Products<br />You'll Love
      </h1>
      <p class="text-slate-300 text-lg max-w-xl">
        Thousands of items at competitive prices. Free shipping on orders over $50.
      </p>
      <div class="flex flex-wrap gap-3 justify-center">
        <RouterLink to="/products">
          <Button size="lg" class="px-8">Shop Now</Button>
        </RouterLink>
        <RouterLink to="/products">
          <Button size="lg" variant="outline" class="px-8 border-white/30 font-semibold text-black/70 hover:bg-white/10">
            View Catalog
          </Button>
        </RouterLink>
      </div>
      <!-- Decorative circles -->
      <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/5 pointer-events-none" />
      <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full bg-white/5 pointer-events-none" />
    </section>

    <!-- Stats Bar -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div v-for="stat in stats" :key="stat.label"
           class="flex flex-col items-center gap-1 p-4 rounded-xl border bg-card text-center">
        <component :is="stat.icon" class="h-6 w-6 text-primary" />
        <span class="font-bold text-xl">{{ stat.value }}</span>
        <span class="text-xs text-muted-foreground">{{ stat.label }}</span>
      </div>
    </section>

    <!-- Categories -->
    <section class="space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">Shop by Category</h2>
        <RouterLink to="/products" class="text-sm text-primary hover:underline">View all</RouterLink>
      </div>

      <div v-if="categoriesLoading" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <Skeleton v-for="i in 4" :key="i" class="h-28 rounded-xl" />
      </div>

      <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <RouterLink
            v-for="cat in categories"
            :key="cat.id"
            :to="`/products?category=${cat.id}`"
            class="group relative flex flex-col items-center justify-center gap-3 rounded-xl border bg-card p-6 text-center transition-all hover:border-primary hover:shadow-md hover:-translate-y-0.5"
        >
          <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
            <Tag class="h-6 w-6 text-primary" />
          </div>
          <span class="font-medium text-sm">{{ cat.name }}</span>
        </RouterLink>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">New Arrivals</h2>
        <RouterLink to="/products" class="text-sm text-primary hover:underline">All products →</RouterLink>
      </div>

      <div v-if="productsLoading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <Skeleton v-for="i in 8" :key="i" class="h-64 rounded-xl" />
      </div>

      <div v-else-if="featuredProducts.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <ProductCard
            v-for="product in featuredProducts"
            :key="product.id"
            :product="product"
            @add-to-cart="handleAddToCart"
        />
      </div>

      <div class="flex justify-center pt-2">
        <RouterLink to="/products">
          <Button variant="outline" size="lg">See All Products</Button>
        </RouterLink>
      </div>
    </section>

    <!-- Promo Banner -->
    <section class="grid md:grid-cols-2 gap-4">
      <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-8 flex flex-col gap-4">
        <Truck class="h-8 w-8" />
        <h3 class="text-xl font-bold">Free Shipping</h3>
        <p class="text-emerald-100 text-sm">On all orders over $50. Fast delivery to your door in 2-3 business days.</p>
        <RouterLink to="/products">
          <Button variant="secondary" size="sm" class="w-fit">Shop Now</Button>
        </RouterLink>
      </div>
      <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-purple-700 text-white p-8 flex flex-col gap-4">
        <ShieldCheck class="h-8 w-8" />
        <h3 class="text-xl font-bold">Buyer Protection</h3>
        <p class="text-violet-100 text-sm">30-day easy returns. Full refund if the item doesn't match the description.</p>
        <RouterLink to="/products">
          <Button variant="secondary" size="sm" class="w-fit">Learn More</Button>
        </RouterLink>
      </div>
    </section>

    <!-- Newsletter -->
    <section class="rounded-2xl border bg-muted/40 px-8 py-12 flex flex-col items-center text-center gap-6">
      <Mail class="h-10 w-10 text-primary" />
      <div class="space-y-2">
        <h2 class="text-2xl font-bold">Stay in the Loop</h2>
        <p class="text-muted-foreground text-sm max-w-md">
          Subscribe to get notified about new products and exclusive deals.
        </p>
      </div>
      <div class="flex w-full max-w-sm gap-2">
        <Input v-model="email" type="email" placeholder="your@email.com" class="flex-1" />
        <Button @click="subscribe">Subscribe</Button>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Skeleton } from '@/components/ui/skeleton';
import ProductCard from '@/components/parts/ProductCard.vue';
import { Tag, Truck, ShieldCheck, Mail, Package, Star, Users, Headphones } from 'lucide-vue-next';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useProductService } from '@/services/productService.js';
import { useCartStore } from '@/store/cartStore.js';
import { storeToRefs } from 'pinia';

const categoryStore = useCategoryStore();
const cartStore     = useCartStore();
const { categories } = storeToRefs(categoryStore);
const { getFeatured } = useProductService();

const featuredProducts = ref([]);
const productsLoading = ref(true);
const categoriesLoading = ref(true);
const email = ref('');

const stats = [
  { icon: Package,    value: '10k+',   label: 'Products' },
  { icon: Users,      value: '50k+',   label: 'Customers' },
  { icon: Star,       value: '4.8',    label: 'Avg Rating' },
  { icon: Headphones, value: '24/7',   label: 'Support' },
];

onMounted(async () => {
  try {
    if (categories.value.length === 0) {
      await categoryStore.load();
    }
  } finally {
    categoriesLoading.value = false;
  }

  try {
    featuredProducts.value = await getFeatured();
  } finally {
    productsLoading.value = false;
  }
});

const handleAddToCart = async (product) => {
  try {
    await cartStore.add(product.id);
  } catch (e) {
    console.error('Failed to add to cart:', e);
  }
};

const subscribe = () => {
  if (email.value) {
    alert(`Thanks! ${email.value} subscribed.`);
    email.value = '';
  }
};
</script>
