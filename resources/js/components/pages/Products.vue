<template>
  <div class="space-y-6 select-none">

    <!-- Page header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold tracking-tight text-stone-900">{{ t('products.title') }}</h1>
        <p v-if="!loading" class="text-xs text-stone-400 mt-0.5">{{ meta.total }} {{ t('products.found') }}</p>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
        <!-- Search — full width on mobile -->
        <div class="relative">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-stone-400 pointer-events-none" />
          <input
            v-model="searchQuery"
            :placeholder="t('products.search')"
            class="h-9 pl-9 pr-3 w-full sm:w-56 text-sm rounded-lg border border-stone-200 bg-white placeholder:text-stone-400 focus:outline-none focus:border-stone-400 transition-colors"
          />
        </div>

        <!-- Secondary controls: filter + per-page + clear -->
        <div class="flex items-center gap-2">
          <!-- Mobile filter button -->
          <Button
            variant="outline"
            size="sm"
            @click="filterStore.openMobileFilter()"
            class="md:hidden h-9 px-3 text-xs font-medium border-stone-200 text-stone-600 hover:bg-stone-100 hover:text-stone-900"
          >
            <SlidersHorizontal class="h-3.5 w-3.5" />
            {{ t('filter.open') }}
            <span v-if="filterStore.hasAnyFilter" class="h-4 w-4 bg-stone-900 text-white text-[9px] rounded-full flex items-center justify-center font-semibold leading-none">
              {{ filterStore.activeCount }}
            </span>
          </Button>

          <!-- Per page -->
          <Select v-model="perPage" @update:model-value="goToPage(1)">
            <SelectTrigger class="h-9 w-20 text-sm border-stone-200">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="12">12</SelectItem>
              <SelectItem value="24">24</SelectItem>
            </SelectContent>
          </Select>

          <!-- Clear filters — pushed to the right on mobile -->
          <Button
            v-if="filterStore.hasAnyFilter"
            variant="ghost"
            size="sm"
            @click="filterStore.reset()"
            class="ml-auto sm:ml-0 h-9 px-3 text-xs text-stone-500 hover:text-red-500 hover:bg-red-50"
          >
            <X class="h-3.5 w-3.5" />
            {{ t('products.clearFilter') }}
          </Button>
        </div>
      </div>
    </div>

    <!-- Active filter chips -->
    <div v-if="filterStore.hasAnyFilter" class="flex flex-wrap gap-2">
      <span
        v-for="color in filterStore.colors" :key="`c-${color}`"
        class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] uppercase tracking-wider font-medium border border-stone-200 rounded-full text-stone-600"
      >
        {{ localeStore.t(color) }}
        <button @click="filterStore.toggle('colors', color)" class="text-stone-400 hover:text-stone-700">×</button>
      </span>
      <span
        v-for="size in filterStore.sizes" :key="`s-${size}`"
        class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] uppercase tracking-wider font-medium border border-stone-200 rounded-full text-stone-600"
      >
        {{ size }}
        <button @click="filterStore.toggle('sizes', size)" class="text-stone-400 hover:text-stone-700">×</button>
      </span>
    </div>

    <!-- Grid -->
    <div v-if="loading" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
      <div v-for="i in parseInt(perPage)" :key="i" class="bg-stone-100 rounded-xl aspect-[3/4] animate-pulse" />
    </div>

    <template v-else>
      <div v-if="products.length" class="grid gap-5 grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        <ProductCard
          v-for="product in products"
          :key="product.id"
          :product="product"
        />
      </div>

      <div v-else class="flex flex-col items-center justify-center py-20 text-center">
        <ShoppingBag class="h-10 w-10 text-stone-300 mb-4" />
        <p class="text-sm font-medium text-stone-900">{{ t('products.empty.title') }}</p>
        <p class="text-xs text-stone-400 mt-1">{{ t('products.empty.hint') }}</p>
      </div>
    </template>

    <!-- Pagination -->
    <div v-if="meta.last_page > 1" class="flex items-center justify-between pt-2">
      <p class="text-xs text-stone-400">
        {{ t('products.page') }} {{ meta.current_page }} {{ t('products.of') }} {{ meta.last_page }}
      </p>

      <div class="flex items-center gap-1">
        <button
          :disabled="meta.current_page === 1"
          @click="goToPage(1)"
          :class="PAGE_NAV_CLASS"
        ><ChevronsLeft class="h-3.5 w-3.5" /></button>
        <button
          :disabled="meta.current_page === 1"
          @click="goToPage(meta.current_page - 1)"
          :class="PAGE_NAV_CLASS"
        ><ChevronLeft class="h-3.5 w-3.5" /></button>

        <button
          v-for="page in visiblePages" :key="page"
          @click="goToPage(page)"
          :class="[
            'h-8 w-8 flex items-center justify-center rounded-lg text-xs font-medium transition-colors',
            page === meta.current_page
              ? 'bg-stone-900 text-white'
              : 'border border-stone-200 text-stone-600 hover:bg-stone-100'
          ]"
        >{{ page }}</button>

        <button
          :disabled="meta.current_page === meta.last_page"
          @click="goToPage(meta.current_page + 1)"
          :class="PAGE_NAV_CLASS"
        ><ChevronRight class="h-3.5 w-3.5" /></button>
        <button
          :disabled="meta.current_page === meta.last_page"
          @click="goToPage(meta.last_page)"
          :class="PAGE_NAV_CLASS"
        ><ChevronsRight class="h-3.5 w-3.5" /></button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useDebounceFn } from '@vueuse/core';
import { Button } from '@/components/ui/button';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import ProductCard from '@/components/parts/ProductCard.vue';
import { ShoppingBag, Search, X, ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight, SlidersHorizontal } from 'lucide-vue-next';
import { useProductService } from '@/services/productService.js';
import { useFilterStore } from '@/store/filterStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useI18n } from '@/i18n';

const PAGE_NAV_CLASS = 'h-8 w-8 flex items-center justify-center rounded-lg border border-stone-200 text-stone-500 hover:bg-stone-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors';

const route           = useRoute();
const { getProducts } = useProductService();
const filterStore     = useFilterStore();
const localeStore     = useLocaleStore();
const { t }           = useI18n();

const products    = ref([]);
const loading     = ref(true);
const searchQuery = ref('');
const perPage     = ref('12');
const meta = ref({ current_page: 1, last_page: 1, per_page: 12, total: 0 });

const fetchProducts = async (page = 1) => {
  loading.value = true;
  try {
    const result = await getProducts({
      ...filterStore.query,
      page,
      perPage: parseInt(perPage.value),
      search:  searchQuery.value || undefined,
    });
    products.value = result.data;
    meta.value     = result.meta;
  } catch (e) {
    console.error('Failed to fetch products:', e);
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => {
  fetchProducts(page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const visiblePages = computed(() => {
  const { current_page: current, last_page: last } = meta.value;
  const pages = [];
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) pages.push(i);
  return pages;
});

watch(searchQuery, useDebounceFn(() => fetchProducts(1), 400));
watch(() => filterStore.query, () => fetchProducts(1), { deep: true });

onMounted(() => {
  const category = parseInt(route.query.category);
  if (category && !filterStore.selectedCategories.includes(category)) {
    filterStore.toggle('selectedCategories', category); // the filter watcher triggers the fetch
  } else {
    fetchProducts(1);
  }
});
</script>
