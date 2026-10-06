<template>
  <article
    class="group relative flex h-full cursor-pointer flex-col gap-3"
    @click="$router.push({ name: 'product-detail', params: { id: product.id } })"
  >
    <!-- Image -->
    <div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-muted ring-1 ring-black/[0.03] transition-shadow duration-500 group-hover:shadow-[0_20px_40px_-20px_rgb(60_40_20/0.35)]">
      <template v-if="images.length">
        <img
          :src="images[0].thumb_url"
          :alt="localeStore.t(product.name)"
          loading="lazy"
          class="absolute inset-0 size-full object-cover transition-all duration-700 ease-out-expo group-hover:scale-105"
          :class="images[1] && 'group-hover:opacity-0'"
        />
        <img
          v-if="images[1]"
          :src="images[1].thumb_url"
          alt=""
          loading="lazy"
          class="absolute inset-0 size-full scale-110 object-cover opacity-0 transition-all duration-700 ease-out-expo group-hover:scale-100 group-hover:opacity-100"
        />
      </template>
      <div v-else class="flex size-full items-center justify-center">
        <Package class="size-10 text-muted-foreground/40" />
      </div>

      <ProductBadges :product="product" class="absolute left-3 top-3" />

      <!-- Quick add -->
      <div class="absolute inset-x-3 bottom-3 translate-y-[calc(100%+1rem)] opacity-0 transition-all duration-500 ease-out-expo group-hover:translate-y-0 group-hover:opacity-100 max-md:translate-y-0 max-md:opacity-100 max-md:inset-x-auto max-md:right-2 max-md:bottom-2">
        <Button
          :disabled="inCart || adding"
          size="lg"
          class="w-full rounded-xl bg-background/80 text-foreground shadow-lg backdrop-blur-md hover:bg-primary hover:text-primary-foreground max-md:size-10 max-md:rounded-full max-md:p-0"
          :class="inCart && 'opacity-100! bg-success text-white'"
          @click.stop="handleAdd"
        >
          <Check v-if="inCart" class="animate-pop" />
          <Loader2 v-else-if="adding" class="animate-spin" />
          <Plus v-else />
          <span class="max-md:sr-only">{{ inCart ? t('card.added') : t('card.add') }}</span>
        </Button>
      </div>
    </div>

    <!-- Info -->
    <div class="flex flex-1 flex-col gap-1 px-0.5">
      <div class="flex items-center justify-between gap-2">
        <p class="truncate text-[11px] font-medium uppercase tracking-[0.15em] text-muted-foreground">
          {{ product.brand?.name ?? localeStore.t(product.category?.name) ?? '' }}
        </p>
        <div v-if="colors.length" class="flex shrink-0 items-center -space-x-1">
          <span
            v-for="c in colors.slice(0, MAX_SWATCHES)"
            :key="c.hex"
            class="size-3.5 rounded-full ring-2 ring-background transition-transform duration-300 group-hover:translate-x-0"
            :style="{ backgroundColor: c.hex }"
          />
          <span v-if="colors.length > MAX_SWATCHES" class="pl-2 text-[10px] text-muted-foreground">+{{ colors.length - MAX_SWATCHES }}</span>
        </div>
      </div>

      <h3 class="line-clamp-2 text-sm font-medium leading-snug transition-colors group-hover:text-brand" :title="localeStore.t(product.name)">
        {{ localeStore.t(product.name) }}
      </h3>

      <div class="mt-auto flex items-baseline gap-2 pt-1">
        <span class="text-base font-semibold tabular-nums" :class="product.old_price && 'text-brand'">{{ formatPrice(product.price) }}</span>
        <span v-if="product.old_price" class="text-xs tabular-nums text-muted-foreground line-through">{{ formatPrice(product.old_price) }}</span>
      </div>
    </div>
  </article>

  <!-- Variant selector -->
  <Dialog v-model:open="selectorOpen">
    <DialogContent class="sm:max-w-sm rounded-2xl">
      <DialogHeader class="flex-row items-center gap-3 space-y-0 text-left">
        <img v-if="images.length" :src="images[0].thumb_url" alt="" class="size-14 rounded-xl object-cover" />
        <div class="min-w-0">
          <DialogTitle class="line-clamp-2 text-base leading-snug">{{ localeStore.t(product.name) }}</DialogTitle>
          <DialogDescription class="mt-1 font-semibold text-foreground">{{ formatPrice(variant?.price ?? product.price) }}</DialogDescription>
        </div>
      </DialogHeader>

      <div class="space-y-5 pt-2">
        <div v-if="hasColors" class="space-y-2.5">
          <p class="text-xs font-medium text-muted-foreground">
            {{ t('filter.color') }}<span v-if="selectedColor" class="text-foreground">: {{ localeStore.t(selectedColorName) }}</span>
          </p>
          <ColorSwatches :colors="colors" :model-value="selectedColor" @update:model-value="selectColor" />
        </div>

        <div v-if="availableSizes.length" class="space-y-2.5">
          <p class="text-xs font-medium text-muted-foreground">{{ t('filter.size') }}</p>
          <SizePicker :sizes="availableSizes" :model-value="selectedSize" @update:model-value="selectSize" />
        </div>

        <Button size="lg" class="w-full rounded-xl" :disabled="!variant || adding" @click="addToCartWithVariant">
          <Loader2 v-if="adding" class="animate-spin" />
          <ShoppingBag v-else />
          {{ adding ? t('product.adding') : t('card.add') }}
        </Button>
      </div>
    </DialogContent>
  </Dialog>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Package, Check, Loader2, Plus, ShoppingBag } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { useCartStore } from '@/store/cartStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useI18n } from '@/i18n';
import { useVariantPicker } from '@/composables/useVariantPicker.js';
import { formatPrice } from '@/lib/format.js';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import ProductBadges from '@/components/parts/ProductBadges.vue';
import ColorSwatches from '@/components/parts/ColorSwatches.vue';
import SizePicker from '@/components/parts/SizePicker.vue';

const MAX_SWATCHES = 5;

const props = defineProps({ product: { type: Object, required: true } });

const { t }       = useI18n();
const cartStore   = useCartStore();
const localeStore = useLocaleStore();

const {
  selectedColor, selectedSize, selectedColorName,
  colors, hasColors, availableSizes, variant, cartPayload,
  selectColor, selectSize, reset,
} = useVariantPicker(() => props.product.variants);

const images       = computed(() => props.product.media_items ?? []);
const inCart       = computed(() => cartStore.itemIds.has(props.product.id));
const hasVariants  = computed(() => (props.product.variants?.length ?? 0) > 0);
const selectorOpen = ref(false);
const adding       = ref(false);

async function addToCart(payload = {}, description) {
  adding.value = true;
  try {
    await cartStore.add(props.product.id, payload);
    selectorOpen.value = false;
    toast.success(localeStore.t(props.product.name), { description });
  } catch (e) {
    console.error(e);
  } finally {
    adding.value = false;
  }
}

const handleAdd = () => {
  if (inCart.value || adding.value) return;

  if (hasVariants.value) {
    reset();
    selectorOpen.value = true;
    return;
  }

  addToCart();
};

const addToCartWithVariant = () => {
  if (!variant.value || adding.value) return;
  const description = [selectedSize.value, localeStore.t(variant.value.color)].filter(Boolean).join(' · ');
  addToCart(cartPayload.value, description || undefined);
};
</script>
