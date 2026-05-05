<template>
  <div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col gap-1">
      <h1 class="text-3xl font-bold">Products</h1>
      <p v-if="!loading" class="text-sm text-muted-foreground">
        {{ meta.total }} items found
      </p>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="relative">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" />
          <Input
              v-model="searchQuery"
              placeholder="Search products..."
              class="pl-9 w-full sm:w-64"
          />
        </div>

        <Button
            v-if="filterStore.hasCategoryFilter || filterStore.hasPriceFilter"
            variant="ghost"
            size="sm"
            @click="clearFilters"
            class="text-destructive hover:text-destructive"
        >
          <X class="h-4 w-4 mr-1" />
          Clear filters
        </Button>
      </div>

      <!-- Per page + Sort -->
      <div class="flex items-center gap-2">
        <Select v-model="perPage" @update:model-value="goToPage(1)">
          <SelectTrigger class="w-24">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="12">12 / page</SelectItem>
            <SelectItem value="24">24 / page</SelectItem>
            <SelectItem value="48">48 / page</SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>

    <!-- Loading skeleton -->
    <div v-if="loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <Skeleton v-for="i in parseInt(perPage)" :key="i" class="h-64 rounded-xl" />
    </div>

    <!-- Product grid -->
    <template v-else>
      <div v-if="products.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <ProductCard
            v-for="product in products"
            :key="product.id"
            :product="product"
            @add-to-cart="handleAddToCart"
        />
      </div>

      <!-- Empty state -->
      <div v-else class="flex h-64 items-center justify-center rounded-lg border border-dashed">
        <div class="text-center space-y-3">
          <ShoppingBag class="h-12 w-12 mx-auto text-muted-foreground" />
          <p class="font-medium">No products found</p>
          <p class="text-sm text-muted-foreground">Try adjusting your filters or search query</p>
        </div>
      </div>
    </template>

    <!-- Pagination -->
    <div v-if="meta.last_page > 1" class="flex items-center justify-between pt-2">
      <p class="text-sm text-muted-foreground">
        Page {{ meta.current_page }} of {{ meta.last_page }}
      </p>

      <div class="flex items-center gap-1">
        <Button
            variant="outline"
            size="icon"
            :disabled="meta.current_page === 1"
            @click="goToPage(1)"
            title="First page"
        >
          <ChevronsLeft class="h-4 w-4" />
        </Button>
        <Button
            variant="outline"
            size="icon"
            :disabled="meta.current_page === 1"
            @click="goToPage(meta.current_page - 1)"
        >
          <ChevronLeft class="h-4 w-4" />
        </Button>

        <Button
            v-for="page in visiblePages"
            :key="page"
            :variant="page === meta.current_page ? 'default' : 'outline'"
            size="icon"
            @click="goToPage(page)"
        >
          {{ page }}
        </Button>

        <Button
            variant="outline"
            size="icon"
            :disabled="meta.current_page === meta.last_page"
            @click="goToPage(meta.current_page + 1)"
        >
          <ChevronRight class="h-4 w-4" />
        </Button>
        <Button
            variant="outline"
            size="icon"
            :disabled="meta.current_page === meta.last_page"
            @click="goToPage(meta.last_page)"
            title="Last page"
        >
          <ChevronsRight class="h-4 w-4" />
        </Button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useDebounceFn } from '@vueuse/core';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import ProductCard from '@/components/parts/ProductCard.vue';
import { ShoppingBag, Search, X, ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-vue-next';
import { useProductService } from '@/services/productService.js';
import { useFilterStore } from '@/store/filterStore.js';
import { useCartStore } from '@/store/cartStore.js';

const route  = useRoute();
const router = useRouter();
const { getProducts } = useProductService();
const filterStore = useFilterStore();
const cartStore   = useCartStore();

const products   = ref([]);
const loading    = ref(true);
const searchQuery = ref('');
const perPage    = ref('12');
const meta = ref({
  current_page: 1,
  last_page: 1,
  per_page: 12,
  total: 0,
});

// ── Fetch ─────────────────────────────────────────────────────────────────────
const fetchProducts = async (page = 1) => {
  loading.value = true;
  try {
    const result = await getProducts({
      page,
      perPage: parseInt(perPage.value),
      search: searchQuery.value || undefined,
      categories: filterStore.selectedCategories,
      priceMin: filterStore.priceRange.min ?? undefined,
      priceMax: filterStore.priceRange.max ?? undefined,
    });
    products.value = result.data;
    meta.value     = result.meta;
  } catch (e) {
    console.error('Failed to fetch products:', e);
  } finally {
    loading.value = false;
  }
};

// ── Pagination ────────────────────────────────────────────────────────────────
const goToPage = (page) => {
  fetchProducts(page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const visiblePages = computed(() => {
  const current = meta.value.current_page;
  const last    = meta.value.last_page;
  const delta   = 2;
  const pages   = [];

  for (let i = Math.max(1, current - delta); i <= Math.min(last, current + delta); i++) {
    pages.push(i);
  }
  return pages;
});

// ── Debounced search ──────────────────────────────────────────────────────────
const debouncedSearch = useDebounceFn(() => fetchProducts(1), 400);
watch(searchQuery, debouncedSearch);

// ── Re-fetch when AsidePanel filters change ───────────────────────────────────
// Watch store state directly (getter fn) — reliable with Pinia Options API splice mutations
watch(
  () => [filterStore.selectedCategories.slice(), filterStore.priceRange.min, filterStore.priceRange.max],
  () => fetchProducts(1),
  { deep: true }
);

// ── Handle ?category= query param from Home page links ───────────────────────
onMounted(async () => {
  if (route.query.category) {
    filterStore.toggleCategory(parseInt(route.query.category));
  }
  await fetchProducts(1);
});

// ── Add to cart ────────────────────────────────────────────────────────────────
const handleAddToCart = async (product) => {
  try {
    await cartStore.add(product.id);
  } catch (e) {
    console.error('Failed to add to cart:', e);
  }
};

const clearFilters = () => filterStore.reset();
</script>
