<template>
  <article
    class="group bg-white rounded-xl overflow-hidden cursor-pointer transition-shadow duration-300 hover:shadow-lg hover:border flex flex-col"
    @click="$router.push(`/product/${product.id}`)"
  >
    <!-- Image -->
    <div class="relative aspect-square overflow-hidden bg-stone-100">
      <img
        v-if="product.media_items?.length"
        :src="product.media_items[0]?.thumb_url"
        :alt="localeStore.t(product.name)"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
      <div v-else class="w-full h-full flex items-center justify-center">
        <Package class="h-10 w-10 text-stone-300" />
      </div>

      <!-- Badges -->
      <div class="absolute top-3 left-3 flex flex-col gap-1">
        <Badge v-if="product.is_new"  class="text-[9px] font-semibold uppercase tracking-widest px-2 py-0.5 bg-blue-500/50 text-white">{{ t('product.badge.new') }}</Badge>
        <Badge v-if="product.is_hit"  class="text-[9px] font-semibold uppercase tracking-widest px-2 py-0.5 bg-amber-500/50 text-white">{{ t('product.badge.hit') }}</Badge>
        <Badge v-if="product.is_sale" class="text-[9px] font-semibold uppercase tracking-widest px-2 py-0.5 bg-rose-500/50 text-white">{{ t('product.badge.sale') }}</Badge>
      </div>

      <!-- Quick add — slides up on hover -->
      <div class="absolute inset-x-0 bottom-0 p-2.5 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out">
        <button
          @click.stop="handleAdd"
          :disabled="inCart || adding"
          :class="[
            'w-full py-2.5 text-xs font-semibold uppercase tracking-widest rounded-lg transition-colors duration-200',
            inCart
              ? 'bg-stone-200 text-stone-500 cursor-default'
              : 'bg-white/95 backdrop-blur-sm text-stone-900 hover:bg-stone-900 hover:text-white'
          ]"
        >
          <span v-if="inCart" class="flex items-center justify-center gap-1.5">
            <Check class="h-3.5 w-3.5" /> {{ t('card.added') }}
          </span>
          <span v-else-if="adding" class="flex items-center justify-center gap-1.5">
            <Loader2 class="h-3.5 w-3.5 animate-spin" />
          </span>
          <span v-else>{{ t('card.add') }}</span>
        </button>
      </div>
    </div>

    <!-- Info -->
    <div class="p-4 flex flex-col flex-1 gap-2">

      <!-- Category / brand + color swatches -->
      <div class="flex justify-between items-center">
        <p class="text-[10px] font-medium uppercase tracking-widest text-stone-400 truncate">
          {{ product.brand?.name ?? localeStore.t(product.category?.name) ?? '' }}
        </p>
        <div v-if="uniqueColors.length" class="flex items-center flex-wrap gap-0.5 shrink-0 ml-2">
          <span
            v-for="c in uniqueColors.slice(0, 8)"
            :key="c"
            class="w-3 h-3 rounded-full border border-stone-200 shrink-0"
            :style="{ backgroundColor: c }"
          />
          <span v-if="uniqueColors.length > 8" class="text-[10px] text-stone-400 leading-none">
            +{{ uniqueColors.length - 8 }}
          </span>
        </div>
      </div>

      <!-- Name with tooltip -->
      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger as-child>
            <h3 class="text-sm font-medium text-stone-900 leading-snug line-clamp-2 text-left cursor-default">
              {{ localeStore.t(product.name) }}
            </h3>
          </TooltipTrigger>
          <TooltipContent side="top" class="max-w-56 text-center text-xs">
            {{ localeStore.t(product.name) }}
          </TooltipContent>
        </Tooltip>
      </TooltipProvider>

      <!-- Price — always at bottom -->
      <div class="flex items-baseline gap-2 mt-auto pt-1">
        <span class="text-base font-semibold text-stone-900">{{ Number(product.price).toFixed(2) }} lei</span>
        <span v-if="product.old_price" class="text-xs line-through text-stone-400">
          {{ Number(product.old_price).toFixed(2) }} lei
        </span>
      </div>

    </div>
  </article>

  <!-- Variant selector dialog -->
  <Dialog v-model:open="selectorOpen">
    <DialogContent class="sm:max-w-xs">
      <DialogHeader>
        <DialogTitle class="text-sm font-semibold leading-snug line-clamp-2">
          {{ localeStore.t(product.name) }}
        </DialogTitle>
      </DialogHeader>

      <div class="space-y-5 pt-1">
        <!-- Colors -->
        <div v-if="variantColors.length" class="space-y-2.5">
          <div class="flex items-center gap-2">
            <span class="text-[10px] font-semibold uppercase tracking-widest text-stone-600">
              {{ t('filter.color') }}
            </span>
            <span v-if="selectedColor" class="text-xs text-stone-500">
              {{ colorLabel(selectedColorName) }}
            </span>
          </div>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="c in variantColors"
              :key="c.hex"
              type="button"
              @click="selectColor(c)"
              class="w-7 h-7 rounded-full transition-all duration-150"
              :class="selectedColor === c.hex
                ? 'ring-2 ring-stone-900 ring-offset-2'
                : 'ring-1 ring-stone-200 hover:ring-stone-400'"
              :style="{ backgroundColor: c.hex }"
            />
          </div>
        </div>

        <!-- Sizes -->
        <div v-if="availableSizes.length" class="space-y-2.5">
          <span class="text-[10px] font-semibold uppercase tracking-widest text-stone-600">
            {{ t('filter.size') }}
          </span>
          <div class="flex flex-wrap gap-1.5">
            <button
              v-for="size in availableSizes"
              :key="size"
              type="button"
              @click="selectedSize = size"
              class="min-w-[2.5rem] px-3 py-1.5 text-xs font-medium border rounded-md transition-colors duration-150"
              :class="selectedSize === size
                ? 'bg-stone-900 text-white border-stone-900'
                : 'border-stone-200 text-stone-600 hover:border-stone-900 hover:text-stone-900'"
            >{{ size }}</button>
          </div>
        </div>

        <button
          @click="addToCartWithVariant"
          :disabled="!pickedVariant || adding"
          class="w-full py-3 text-xs font-semibold uppercase tracking-widest rounded-lg transition-colors duration-200"
          :class="pickedVariant && !adding
            ? 'bg-stone-900 text-white hover:bg-stone-700'
            : 'bg-stone-100 text-stone-400 cursor-not-allowed'"
        >
          <span v-if="adding" class="flex items-center justify-center gap-2">
            <Loader2 class="h-3.5 w-3.5 animate-spin" /> {{ t('product.adding') }}
          </span>
          <span v-else>{{ t('card.add') }}</span>
        </button>
      </div>
    </DialogContent>
  </Dialog>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Package, Check, Loader2 } from 'lucide-vue-next';
import { useCartStore } from '@/store/cartStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useI18n } from '@/i18n';
import { Badge } from '@/components/ui/badge';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Tooltip, TooltipTrigger, TooltipContent, TooltipProvider } from '@/components/ui/tooltip';
import { toast } from 'vue-sonner';

const props = defineProps({ product: { type: Object, required: true } });

const { t }       = useI18n();
const cartStore   = useCartStore();
const localeStore = useLocaleStore();

const inCart = computed(() => cartStore.itemIds.has(props.product.id));

// Unique hex list for the card swatches display
const uniqueColors = computed(() => {
  const seen = new Set()
  for (const v of (props.product.variants ?? [])) {
    if (v.color_hex) seen.add(v.color_hex)
  }
  return [...seen]
});

// ── Variant selector ──────────────────────────────────────────────────────
const selectorOpen  = ref(false)
const selectedColor = ref(null)   // hex string | null
const selectedSize  = ref(null)   // string | null
const adding        = ref(false)

const hasVariants = computed(() => (props.product.variants?.length ?? 0) > 0)

// Unique colors with name for the selector swatches
const variantColors = computed(() => {
  const seen = new Map()
  for (const v of (props.product.variants ?? [])) {
    if (v.color_hex && !seen.has(v.color_hex)) {
      seen.set(v.color_hex, { hex: v.color_hex, name: v.color ?? '' })
    }
  }
  return [...seen.values()]
})

// All sizes across all variants (to determine if size selection is required)
const allSizes = computed(() =>
  [...new Set((props.product.variants ?? []).filter(v => v.size).map(v => v.size))]
)

// Sizes for currently selected color (or all if no color selected)
const availableSizes = computed(() => {
  const pool = selectedColor.value
    ? (props.product.variants ?? []).filter(v => v.color_hex === selectedColor.value)
    : (props.product.variants ?? [])
  return [...new Set(pool.filter(v => v.size).map(v => v.size))]
})

// The name string of the currently selected color (for display)
const selectedColorName = computed(() =>
  variantColors.value.find(c => c.hex === selectedColor.value)?.name ?? ''
)

// Matched variant — null when required fields not yet chosen
const pickedVariant = computed(() =>
  (props.product.variants ?? []).find(v => {
    const colorOK = variantColors.value.length === 0 || v.color_hex === selectedColor.value
    const sizeOK  = allSizes.value.length === 0      || v.size === selectedSize.value
    return colorOK && sizeOK
  }) ?? null
)

function colorLabel(nameJson) {
  if (!nameJson) return ''
  try {
    const obj = typeof nameJson === 'string' ? JSON.parse(nameJson) : nameJson
    return localeStore.t(obj)
  } catch {
    return nameJson
  }
}

function selectColor(c) {
  selectedColor.value = c.hex
  // reset size if it's not available for the new color
  if (selectedSize.value && !availableSizes.value.includes(selectedSize.value)) {
    selectedSize.value = null
  }
}

const handleAdd = async () => {
  if (inCart.value || adding.value) return

  if (hasVariants.value) {
    selectedColor.value = null
    selectedSize.value  = null
    selectorOpen.value  = true
    return
  }

  adding.value = true
  try {
    await cartStore.add(props.product.id)
    toast.success(localeStore.t(props.product.name))
  } catch (e) {
    console.error(e)
  } finally {
    adding.value = false
  }
}

const addToCartWithVariant = async () => {
  if (!pickedVariant.value || adding.value) return
  adding.value = true
  try {
    await cartStore.add(props.product.id, {
      variant_id: pickedVariant.value.id,
      color:      pickedVariant.value.color     ?? null,
      color_hex:  pickedVariant.value.color_hex ?? null,
      size:       pickedVariant.value.size      ?? null,
    })
    selectorOpen.value = false
    const parts = [
      selectedSize.value,
      colorLabel(pickedVariant.value.color),
    ].filter(Boolean).join(' · ')
    toast.success(localeStore.t(props.product.name), { description: parts || undefined })
  } catch (e) {
    console.error(e)
  } finally {
    adding.value = false
  }
}
</script>