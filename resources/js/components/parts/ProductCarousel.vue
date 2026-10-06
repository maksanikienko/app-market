<template>
  <Carousel :opts="{ align: 'start', loop: products.length > 4 }" :plugins="[autoplay]" class="space-y-6">
    <SectionHeading v-bind="$attrs">
      <div v-if="products.length" class="flex shrink-0 gap-2">
        <CarouselPrevious class="static size-10 translate-y-0 bg-background hover:bg-primary hover:text-primary-foreground transition-colors" />
        <CarouselNext class="static size-10 translate-y-0 bg-background hover:bg-primary hover:text-primary-foreground transition-colors" />
      </div>
    </SectionHeading>

    <div v-if="loading" class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5">
      <ProductCardSkeleton v-for="i in 5" :key="i" :class="i > 2 && 'hidden md:block'" />
    </div>

    <CarouselContent v-else class="-ml-4 py-1">
      <CarouselItem
        v-for="(product, i) in products"
        :key="product.id"
        class="basis-1/2 pl-4 md:basis-1/3 lg:basis-1/4 2xl:basis-1/5 animate-rise stagger"
        :style="{ '--i': i }"
      >
        <ProductCard :product="product" />
      </CarouselItem>
    </CarouselContent>
  </Carousel>
</template>

<script setup>
import Autoplay from 'embla-carousel-autoplay';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import ProductCard from '@/components/parts/ProductCard.vue';
import ProductCardSkeleton from '@/components/parts/ProductCardSkeleton.vue';
import SectionHeading from '@/components/parts/SectionHeading.vue';

defineOptions({ inheritAttrs: false });

defineProps({
  products: { type: Array, required: true },
  loading:  { type: Boolean, default: false },
});

const autoplay = Autoplay({ delay: 4500, stopOnMouseEnter: true, stopOnInteraction: false });
</script>
