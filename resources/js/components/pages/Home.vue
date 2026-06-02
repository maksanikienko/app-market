<template>
  <div class="space-y-14 pb-16">

    <!-- Store Title Hero -->
    <section class="relative overflow-hidden rounded-2xl text-white min-h-[420px] md:min-h-[500px] flex items-center">
      <img :src="`/storage/home/main-image.png`" alt="" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-stone-950/55" />

      <div class="relative w-full flex flex-col items-center text-center gap-5 px-8 py-16 md:py-20">

        <h1 class="text-3xl md:text-5xl font-semibold tracking-tight leading-tight max-w-2xl drop-shadow-md">
          {{ t('home.store.name') }}
        </h1>

        <p class="text-stone-300 text-sm max-w-sm leading-relaxed">
          {{ t('home.store.tagline') }}
        </p>

        <RouterLink to="/products">
          <button class="mt-1 bg-white text-stone-900 hover:bg-stone-100 rounded-lg px-8 py-3 text-sm font-semibold transition-colors">
            {{ t('home.store.cta') }}
          </button>
        </RouterLink>
      </div>
    </section>

    <!-- Categories -->
    <section class="space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold tracking-tight text-stone-900">{{ t('home.cats.title') }}</h2>
        <RouterLink to="/products" class="text-[10px] uppercase tracking-widest font-medium text-stone-400 hover:text-stone-700 transition-colors">
          {{ t('home.cats.viewAll') }}
        </RouterLink>
      </div>

      <div v-if="categoriesLoading" class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <Skeleton v-for="i in 6" :key="i" class="aspect-square rounded-xl" />
      </div>

      <div v-else class="grid grid-cols-2 md:grid-cols-6 gap-4">
        <RouterLink
            v-for="cat in categories"
            :key="cat.id"
            :to="`/products?category=${cat.id}`"
            class="group bg-white rounded-xl overflow-hidden border border-stone-200 hover:shadow-lg hover:border-stone-300 transition-all duration-300"
        >
          <div class="aspect-square overflow-hidden bg-stone-100">
            <img
                :src="`/storage/categories/${cat.slug}.png`"
                :alt="localeStore.t(cat.name)"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
          </div>
          <div class="px-4 py-3 text-center border-t border-stone-100">
            <span class="font-semibold text-sm text-stone-900">{{ localeStore.t(cat.name) }}</span>
          </div>
        </RouterLink>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">{{ t('home.arrivals.title') }}</h2>
        <RouterLink to="/products" class="text-sm text-primary hover:underline">{{ t('home.arrivals.all') }}</RouterLink>
      </div>

      <div v-if="productsLoading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <Skeleton v-for="i in 8" :key="i" class="h-64 rounded-xl" />
      </div>

      <div v-else-if="featuredProducts.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
        <ProductCard
            v-for="product in featuredProducts"
            :key="product.id"
            :product="product"
        />
      </div>

      <div class="flex justify-center pt-2">
        <RouterLink to="/products">
          <Button variant="outline" size="lg">{{ t('home.arrivals.seeAll') }}</Button>
        </RouterLink>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import ProductCard from '@/components/parts/ProductCard.vue';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useProductService } from '@/services/productService.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useI18n } from '@/i18n';
import { storeToRefs } from 'pinia';

const categoryStore = useCategoryStore();
const localeStore   = useLocaleStore();
const { t }         = useI18n();
const { categories } = storeToRefs(categoryStore);
const { getFeatured } = useProductService();

const featuredProducts = ref([]);
const productsLoading  = ref(true);
const categoriesLoading = ref(true);

onMounted(async () => {
  try {
    if (categories.value.length === 0) await categoryStore.load();
  } finally {
    categoriesLoading.value = false;
  }

  try {
    featuredProducts.value = await getFeatured();
  } finally {
    productsLoading.value = false;
  }
});


</script>