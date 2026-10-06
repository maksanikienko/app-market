<template>
  <div class="space-y-16 md:space-y-24 pb-8 select-none">

    <!-- Hero -->
    <section class="relative -mt-2 flex min-h-[480px] md:min-h-[600px] items-end overflow-hidden rounded-3xl bg-primary text-white">
      <img :src="HERO_IMAGE" alt="" class="animate-ken-burns absolute inset-0 size-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-black/5" />
      <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent" />

      <div class="relative w-full p-6 sm:p-10 md:p-14">
        <div class="max-w-2xl space-y-5">
          <Badge class="animate-rise rounded-full border-white/25 bg-white/10 px-3.5 py-1 text-xs font-medium text-white backdrop-blur-md">
            <Sparkles class="size-3.5" /> {{ t('home.store.eyebrow') }}
          </Badge>

          <h1 class="animate-rise stagger font-display text-5xl sm:text-6xl md:text-7xl font-semibold leading-[0.95] tracking-tight" style="--i: 1">
            {{ t('home.store.name') }}
          </h1>

          <p class="animate-rise stagger max-w-md text-base text-white/75 leading-relaxed" style="--i: 2">
            {{ t('home.store.tagline') }}
          </p>

          <div class="animate-rise stagger flex flex-wrap gap-3 pt-2" style="--i: 3">
            <Button size="lg" as-child class="group h-12 rounded-full bg-white px-7 text-foreground hover:bg-white/90">
              <RouterLink :to="{ name: 'products' }">
                {{ t('home.store.cta') }}
                <ArrowRight class="transition-transform duration-300 group-hover:translate-x-1" />
              </RouterLink>
            </Button>
            <Button size="lg" variant="outline" as-child class="h-12 rounded-full border-white/30 bg-white/10 px-7 text-white backdrop-blur-md hover:bg-white/20 hover:text-white">
              <RouterLink :to="{ name: 'contact' }"><MapPin /> {{ t('home.store.contact') }}</RouterLink>
            </Button>
          </div>
        </div>
      </div>
    </section>

    <!-- Category marquee -->
    <div v-if="categories.length" class="relative -mx-4 sm:-mx-6 lg:-mx-10 overflow-hidden border-y py-5 [mask-image:linear-gradient(90deg,transparent,black_10%,black_90%,transparent)]">
      <div class="animate-marquee flex w-max hover:[animation-play-state:paused]">
        <div v-for="n in 2" :key="n" class="flex shrink-0 items-center" :aria-hidden="n === 2">
          <template v-for="i in 3" :key="i">
            <RouterLink
              v-for="cat in categories"
              :key="`${i}-${cat.id}`"
              :to="{ name: 'products', query: { category: cat.id } }"
              class="flex items-center gap-8 px-4 font-display text-3xl md:text-4xl italic text-foreground/80 transition-colors hover:text-brand"
            >
              {{ localeStore.t(cat.name) }}
              <span class="text-base not-italic text-brand">✦</span>
            </RouterLink>
          </template>
        </div>
      </div>
    </div>

    <!-- Categories bento -->
    <section class="space-y-8">
      <SectionHeading :eyebrow="t('nav.categories')" :title="t('home.cats.title')" :to="{ name: 'products' }" :link-label="t('home.cats.viewAll')" />

      <div v-if="categoryStore.isLoading" class="grid grid-cols-2 md:grid-cols-4 auto-rows-[180px] md:auto-rows-[220px] gap-4">
        <Skeleton v-for="i in 5" :key="i" class="rounded-2xl" :class="i === 1 && 'col-span-2 row-span-2'" />
      </div>

      <div v-else class="grid grid-cols-2 md:grid-cols-4 auto-rows-[180px] md:auto-rows-[220px] gap-4">
        <RouterLink
          v-for="(cat, i) in categories"
          :key="cat.id"
          :to="{ name: 'products', query: { category: cat.id } }"
          class="animate-rise stagger group relative overflow-hidden rounded-2xl bg-muted"
          :class="i === 0 && 'col-span-2 row-span-2'"
          :style="{ '--i': i }"
        >
          <img
            :src="`/storage/categories/${cat.slug}.png`"
            :alt="localeStore.t(cat.name)"
            loading="lazy"
            class="absolute inset-0 size-full object-cover transition-transform duration-1000 ease-out-expo group-hover:scale-110"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 transition-opacity duration-500 group-hover:from-black/70" />
          <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 p-4 md:p-5 text-white">
            <span class="font-display font-semibold leading-none" :class="i === 0 ? 'text-3xl md:text-5xl' : 'text-xl md:text-2xl'">
              {{ localeStore.t(cat.name) }}
            </span>
            <span class="grid size-9 shrink-0 place-items-center rounded-full bg-white/15 backdrop-blur-md transition-all duration-500 ease-spring group-hover:rotate-[-45deg] group-hover:bg-white group-hover:text-foreground">
              <ArrowRight class="size-4" />
            </span>
          </div>
        </RouterLink>
      </div>
    </section>

    <!-- New arrivals -->
    <ProductCarousel
      :products="featuredProducts"
      :loading="productsLoading"
      :eyebrow="t('product.badge.new')"
      :title="t('home.arrivals.title')"
    />

    <!-- Closing banner -->
    <section class="relative overflow-hidden rounded-3xl bg-primary px-6 py-14 md:px-14 md:py-20 text-primary-foreground">
      <div class="pointer-events-none absolute -right-24 -top-24 size-96 rounded-full bg-brand/40 blur-3xl" />
      <div class="pointer-events-none absolute -bottom-32 left-1/3 size-80 rounded-full bg-brand/20 blur-3xl" />

      <div class="relative flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
        <div class="max-w-xl space-y-4">
          <h2 class="font-display text-4xl md:text-6xl font-semibold leading-[0.95] tracking-tight">{{ t('home.banner.title') }}</h2>
          <p class="text-primary-foreground/70 leading-relaxed">{{ t('home.banner.text') }}</p>
        </div>
        <Button size="lg" as-child class="group h-12 shrink-0 rounded-full bg-brand px-8 text-brand-foreground hover:bg-brand/90">
          <RouterLink :to="{ name: 'products' }">
            {{ t('home.arrivals.seeAll') }}
            <ArrowRight class="transition-transform duration-300 group-hover:translate-x-1" />
          </RouterLink>
        </Button>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import { storeToRefs } from 'pinia';
import { ArrowRight, MapPin, Sparkles } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import ProductCarousel from '@/components/parts/ProductCarousel.vue';
import SectionHeading from '@/components/parts/SectionHeading.vue';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useProductService } from '@/services/productService.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useI18n } from '@/i18n';

const HERO_IMAGE = '/storage/home/main-image.png';

const categoryStore = useCategoryStore();
const localeStore   = useLocaleStore();
const { t }         = useI18n();
const { categories } = storeToRefs(categoryStore);
const { getFeatured } = useProductService();

const featuredProducts = ref([]);
const productsLoading  = ref(true);

onMounted(async () => {
  categoryStore.load();
  try {
    featuredProducts.value = await getFeatured();
  } finally {
    productsLoading.value = false;
  }
});
</script>
