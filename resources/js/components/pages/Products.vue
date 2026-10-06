<template>
  <div class="space-y-8 select-none">

    <!-- Page header -->
    <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
      <div class="space-y-2">
        <h1 class="font-display text-4xl md:text-5xl font-semibold leading-none tracking-tight">{{ t('products.title') }}</h1>
        <p class="h-5 text-sm text-muted-foreground">
          <Transition mode="out-in" enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-1" leave-active-class="transition duration-150" leave-to-class="opacity-0">
            <span v-if="!loading" :key="meta.total"><span class="font-semibold text-foreground tabular-nums">{{ meta.total }}</span> {{ t('products.found') }}</span>
          </Transition>
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <div class="relative w-full sm:w-72">
          <Search class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
          <Input v-model="searchQuery" :placeholder="t('products.search')" class="h-10 rounded-full bg-card pl-10 pr-9 shadow-none" />
          <button v-if="searchQuery" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground" @click="searchQuery = ''">
            <X class="size-4" />
          </button>
        </div>

        <Button variant="outline" class="h-10 rounded-full md:hidden" @click="setOpenMobile(true)">
          <SlidersHorizontal />
          {{ t('filter.open') }}
          <span v-if="filterStore.hasAnyFilter" class="grid size-5 place-items-center rounded-full bg-brand text-[10px] font-semibold text-brand-foreground">
            {{ filterStore.activeCount }}
          </span>
        </Button>

        <Select v-model="perPage" @update:model-value="goToPage(1)">
          <SelectTrigger class="h-10! w-24 rounded-full bg-card">
            <LayoutGrid class="size-4 text-muted-foreground" />
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="n in PER_PAGE" :key="n" :value="n">{{ n }}</SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>

    <!-- Active filter chips -->
    <TransitionGroup v-if="chips.length" name="list" tag="div" class="relative flex flex-wrap items-center gap-2">
      <Badge
        v-for="chip in chips"
        :key="chip.key"
        variant="secondary"
        class="h-8 gap-1.5 rounded-full border-0 pl-3 pr-1.5 text-xs font-medium"
      >
        <span v-if="chip.hex" class="size-3 rounded-full ring-1 ring-black/10" :style="{ backgroundColor: chip.hex }" />
        {{ chip.label }}
        <button class="grid size-5 place-items-center rounded-full transition-colors hover:bg-foreground hover:text-background" @click="chip.remove()">
          <X class="size-3" />
        </button>
      </Badge>
      <Button key="reset" variant="ghost" size="sm" class="h-8 rounded-full text-xs text-muted-foreground hover:text-brand" @click="filterStore.reset()">
        {{ t('products.clearFilter') }}
      </Button>
    </TransitionGroup>

    <!-- Grid -->
    <div v-if="loading" :class="GRID_CLASS">
      <ProductCardSkeleton v-for="i in Number(perPage)" :key="i" />
    </div>

    <template v-else>
      <div v-if="products.length" :class="GRID_CLASS">
        <ProductCard
          v-for="(product, i) in products"
          :key="product.id"
          :product="product"
          class="animate-rise stagger"
          :style="{ '--i': i % 12 }"
        />
      </div>

      <div v-else class="animate-rise flex flex-col items-center justify-center gap-4 rounded-3xl border border-dashed py-24 text-center">
        <div class="grid size-16 place-items-center rounded-full bg-muted">
          <ShoppingBag class="size-7 text-muted-foreground" />
        </div>
        <div class="space-y-1">
          <p class="font-display text-2xl font-semibold">{{ t('products.empty.title') }}</p>
          <p class="text-sm text-muted-foreground">{{ t('products.empty.hint') }}</p>
        </div>
        <Button v-if="filterStore.hasAnyFilter" variant="outline" class="rounded-full" @click="filterStore.reset()">
          <RotateCcw /> {{ t('products.clearFilter') }}
        </Button>
      </div>
    </template>

    <!-- Pagination -->
    <Pagination
      v-if="meta.last_page > 1"
      v-slot="{ page }"
      :total="meta.total"
      :items-per-page="meta.per_page"
      :page="meta.current_page"
      :sibling-count="1"
      show-edges
      class="pt-4"
      @update:page="goToPage"
    >
      <PaginationContent v-slot="{ items }">
        <PaginationPrevious class="rounded-full"><ChevronLeft /></PaginationPrevious>
        <template v-for="(item, index) in items" :key="index">
          <PaginationItem
            v-if="item.type === 'page'"
            :value="item.value"
            :is-active="item.value === page"
            class="rounded-full tabular-nums data-[selected]:bg-primary data-[selected]:text-primary-foreground data-[selected]:border-primary"
          >{{ item.value }}</PaginationItem>
          <PaginationEllipsis v-else :index="index" />
        </template>
        <PaginationNext class="rounded-full"><ChevronRight /></PaginationNext>
      </PaginationContent>
    </Pagination>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useDebounceFn } from '@vueuse/core';
import { ShoppingBag, Search, X, SlidersHorizontal, LayoutGrid, RotateCcw, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { useSidebar } from '@/components/ui/sidebar';
import ProductCard from '@/components/parts/ProductCard.vue';
import ProductCardSkeleton from '@/components/parts/ProductCardSkeleton.vue';
import { useProductService } from '@/services/productService.js';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useFilterStore } from '@/store/filterStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useI18n } from '@/i18n';

const GRID_CLASS = 'grid grid-cols-2 gap-x-4 gap-y-8 md:gap-x-6 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5';
const PER_PAGE   = ['12', '24', '48'];

const route           = useRoute();
const { getProducts } = useProductService();
const categoryStore   = useCategoryStore();
const filterStore     = useFilterStore();
const localeStore     = useLocaleStore();
const { t }           = useI18n();
const { setOpenMobile } = useSidebar();

const products    = ref([]);
const loading     = ref(true);
const searchQuery = ref('');
const perPage     = ref('12');
const meta = ref({ current_page: 1, last_page: 1, per_page: 12, total: 0 });

const chips = computed(() => [
  ...filterStore.selectedCategories.map(id => ({
    key:    `cat-${id}`,
    label:  localeStore.t(categoryStore.categories.find(c => c.id === id)?.name),
    remove: () => filterStore.toggle('selectedCategories', id),
  })),
  ...filterStore.colors.map(color => ({
    key:    `color-${localeStore.t(color)}`,
    label:  localeStore.t(color),
    remove: () => filterStore.toggle('colors', color),
  })),
  ...filterStore.sizes.map(size => ({
    key:    `size-${size}`,
    label:  size,
    remove: () => filterStore.toggle('sizes', size),
  })),
]);

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

watch(searchQuery, useDebounceFn(() => fetchProducts(1), 400));
watch(() => filterStore.query, () => fetchProducts(1), { deep: true });

onMounted(() => {
  categoryStore.load();
  const category = parseInt(route.query.category);
  if (category && !filterStore.selectedCategories.includes(category)) {
    filterStore.selectCategory(category); // the filter watcher triggers the fetch
  } else {
    fetchProducts(1);
  }
});
</script>
