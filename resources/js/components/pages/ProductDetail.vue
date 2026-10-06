<template>
  <div v-if="loading" class="grid gap-10 lg:grid-cols-[1.1fr_1fr]">
    <Skeleton class="aspect-[4/5] rounded-3xl" />
    <div class="space-y-5 pt-4">
      <Skeleton class="h-5 w-24 rounded-full" />
      <Skeleton class="h-12 w-3/4" />
      <Skeleton class="h-8 w-1/3" />
      <Skeleton class="h-24 w-full rounded-2xl" />
      <Skeleton class="h-14 w-full rounded-2xl" />
    </div>
  </div>

  <div v-else-if="product" class="space-y-20 select-none">
    <div class="grid gap-10 lg:grid-cols-[1.1fr_1fr] lg:gap-14">

      <!-- Gallery -->
      <div class="animate-rise space-y-3 lg:sticky lg:top-24 lg:self-start">
        <div class="group relative overflow-hidden rounded-3xl bg-muted">
          <Carousel v-if="images.length" :opts="{ loop: images.length > 1 }" class="w-full" @init-api="onCarouselInit">
            <CarouselContent class="-ml-0">
              <CarouselItem v-for="(img, i) in images" :key="img.id" class="pl-0">
                <div class="relative aspect-[4/5] overflow-hidden">
                  <!-- Blurred backdrop fills the letterbox with the photo's own colors -->
                  <img :src="img.thumb_url" alt="" aria-hidden="true" class="pointer-events-none absolute inset-0 size-full scale-125 object-cover opacity-70 blur-2xl saturate-150" />
                  <img
                    :src="img.original_url"
                    :alt="localeStore.t(product.name)"
                    loading="lazy"
                    class="relative size-full cursor-zoom-in object-contain transition-transform duration-700 ease-out-expo hover:scale-[1.03]"
                    @click="openLightbox(i)"
                    @load="onImageLoad($event, img.id)"
                  />
                </div>
              </CarouselItem>
            </CarouselContent>

            <template v-if="images.length > 1">
              <CarouselPrevious class="bg-background/80 backdrop-blur-md left-4 size-10 border-0 shadow-md md:opacity-0 md:-translate-x-2 transition-all duration-300 md:group-hover:opacity-100 md:group-hover:translate-x-0" />
              <CarouselNext class="bg-background/80 backdrop-blur-md right-4 size-10 border-0 shadow-md md:opacity-0 md:translate-x-2 transition-all duration-300 md:group-hover:opacity-100 md:group-hover:translate-x-0" />

              <!-- Progress dots -->
              <div class="absolute inset-x-0 bottom-4 z-10 flex justify-center gap-1.5">
                <button
                  v-for="(img, i) in images"
                  :key="img.id"
                  :aria-label="`${i + 1}`"
                  class="h-1.5 rounded-full bg-white/60 transition-all duration-500 ease-out-expo"
                  :class="i === activeSlide ? 'w-6 bg-white' : 'w-1.5'"
                  @click="carouselApi?.scrollTo(i)"
                />
              </div>
            </template>
          </Carousel>

          <div v-else class="flex aspect-[4/5] items-center justify-center">
            <Package class="size-16 text-muted-foreground/40" />
          </div>

          <ProductBadges :product="product" class="absolute left-4 top-4 z-10" />
        </div>

        <!-- Thumbnails -->
        <div v-if="images.length > 1" class="grid grid-cols-5 gap-2.5">
          <button
            v-for="(img, i) in images"
            :key="img.id"
            class="aspect-square overflow-hidden rounded-xl ring-offset-2 ring-offset-background transition-all duration-300"
            :class="i === activeSlide ? 'ring-2 ring-brand' : 'opacity-55 hover:opacity-100'"
            @click="carouselApi?.scrollTo(i)"
          >
            <img :src="img.thumb_url" :alt="localeStore.t(product.name)" loading="lazy" class="size-full object-cover" />
          </button>
        </div>
      </div>

      <!-- Details -->
      <div class="space-y-8">
        <div class="animate-rise stagger space-y-3" style="--i: 1">
          <p v-if="product.category" class="text-xs font-medium uppercase tracking-[0.2em] text-brand">
            {{ localeStore.t(product.category.name) }}
          </p>
          <h1 class="font-display text-4xl md:text-5xl font-semibold leading-[1.02] tracking-tight">{{ localeStore.t(product.name) }}</h1>
          <p v-if="product.short_description" class="text-muted-foreground leading-relaxed">
            {{ localeStore.t(product.short_description) }}
          </p>
        </div>

        <!-- Price -->
        <div class="animate-rise stagger flex flex-wrap items-baseline gap-3" style="--i: 2">
          <span class="text-3xl font-semibold tabular-nums" :class="product.old_price && 'text-brand'">{{ formatPrice(displayPrice) }}</span>
          <template v-if="product.old_price">
            <span class="text-lg tabular-nums text-muted-foreground line-through">{{ formatPrice(product.old_price) }}</span>
            <Badge class="rounded-full border-0 bg-brand/15 text-brand">
              -{{ Math.round((1 - product.price / product.old_price) * 100) }}%
            </Badge>
          </template>
        </div>

        <Separator />

        <!-- Variant selector -->
        <div v-if="hasColors || hasSizes" class="animate-rise stagger space-y-6" style="--i: 3">
          <div v-if="hasColors" class="space-y-3">
            <p class="text-sm font-medium transition-colors" :class="showValidation && !selectedColor && 'text-destructive'">
              {{ t('product.color') }}:
              <span class="font-normal text-muted-foreground">{{ selectedColor ? localeStore.t(selectedColorName) : '—' }}</span>
            </p>
            <ColorSwatches :colors="colors" :model-value="selectedColor" size="lg" @update:model-value="toggleColor" />
          </div>

          <div v-if="hasSizes" class="space-y-3">
            <p class="text-sm font-medium transition-colors" :class="showValidation && !selectedSize && 'text-destructive'">
              {{ t('product.size') }}
            </p>
            <SizePicker :sizes="availableSizes" :model-value="selectedSize" @update:model-value="toggleSize" />
          </div>

          <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-1" leave-active-class="transition duration-150" leave-to-class="opacity-0">
            <p v-if="showValidation && !isComplete" class="flex items-center gap-2 text-sm text-destructive">
              <CircleAlert class="size-4" /> {{ t('product.choose_params') }}
            </p>
            <p v-else-if="variant" class="flex items-center gap-2 text-sm font-medium" :class="variant.stock > 0 ? 'text-success' : 'text-destructive'">
              <span class="relative flex size-2">
                <span v-if="variant.stock > 0" class="absolute inline-flex size-full animate-ping rounded-full bg-success opacity-60" />
                <span class="relative inline-flex size-2 rounded-full" :class="variant.stock > 0 ? 'bg-success' : 'bg-destructive'" />
              </span>
              {{ variant.stock > 0 ? `${t('product.in_stock')}: ${variant.stock} ${t('product.pcs')}` : t('product.not_in_stock') }}
            </p>
          </Transition>
        </div>

        <!-- Quantity + Add to cart -->
        <div class="animate-rise stagger flex gap-3" style="--i: 4">
          <QuantityStepper v-model="quantity" :max="maxQuantity" size="lg" />

          <Button
            size="lg"
            class="h-12 flex-1 rounded-full text-sm transition-all duration-300"
            :variant="isInCart ? 'secondary' : 'default'"
            :disabled="adding || isInCart || variant?.stock === 0"
            @click="addToCart"
          >
            <Check v-if="isInCart" class="animate-pop" />
            <Loader2 v-else-if="adding" class="animate-spin" />
            <ShoppingBag v-else />
            {{ adding ? t('product.adding') : isInCart ? t('product.added') : t('product.addToCart') }}
          </Button>
        </div>

        <!-- Delivery perk -->
        <div class="animate-rise stagger flex items-center gap-3 rounded-2xl bg-muted/70 p-4 text-sm" style="--i: 5">
          <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-background"><Truck class="size-5 text-brand" /></span>
          <span><span class="font-medium">{{ t('product.spec.delivery') }}</span> · <span class="text-muted-foreground">{{ t('product.spec.days') }}</span></span>
        </div>

        <!-- Specs -->
        <Accordion type="single" collapsible default-value="specs" class="animate-rise stagger" style="--i: 6">
          <AccordionItem value="specs">
            <AccordionTrigger class="text-base font-medium hover:no-underline">{{ t('product.specs') }}</AccordionTrigger>
            <AccordionContent>
              <dl class="divide-y text-sm">
                <div v-for="spec in specs" :key="spec.label" class="flex justify-between gap-4 py-2.5">
                  <dt class="text-muted-foreground">{{ spec.label }}</dt>
                  <dd class="text-right font-medium">{{ spec.value }}</dd>
                </div>
              </dl>
            </AccordionContent>
          </AccordionItem>
        </Accordion>
      </div>
    </div>

    <!-- Featured carousel -->
    <ProductCarousel
      v-if="featured.length"
      :products="featured"
      :eyebrow="t('product.badge.new')"
      :title="t('home.arrivals.title')"
    />
  </div>

  <!-- Not found -->
  <div v-else class="flex flex-col items-center justify-center gap-4 rounded-3xl border border-dashed py-24 text-center">
    <Package class="size-12 text-muted-foreground/50" />
    <p class="font-display text-2xl font-semibold">{{ t('product.notFound') }}</p>
    <Button variant="outline" class="rounded-full" as-child>
      <RouterLink :to="{ name: 'products' }"><ArrowLeft /> {{ t('product.backTo') }}</RouterLink>
    </Button>
  </div>
</template>

<script setup>
import 'photoswipe/style.css'
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import PhotoSwipe from 'photoswipe'
import { Button } from '@/components/ui/button'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import ProductBadges from '@/components/parts/ProductBadges.vue'
import ProductCarousel from '@/components/parts/ProductCarousel.vue'
import ColorSwatches from '@/components/parts/ColorSwatches.vue'
import SizePicker from '@/components/parts/SizePicker.vue'
import QuantityStepper from '@/components/parts/QuantityStepper.vue'
import { ArrowLeft, Check, CircleAlert, Loader2, Package, ShoppingBag, Truck } from 'lucide-vue-next'
import { useProductService } from '@/services/productService.js'
import { useCartStore } from '@/store/cartStore.js'
import { useClassifierService } from '@/services/classifierService.js'
import { useLocaleStore } from '@/store/localeStore.js'
import { useI18n } from '@/i18n'
import { useVariantPicker } from '@/composables/useVariantPicker.js'
import { formatPrice } from '@/lib/format.js'
import { MAX_CART_QUANTITY } from '@/config/cart.js'
import { toast } from 'vue-sonner'

const route        = useRoute()
const { getById, getFeatured } = useProductService()
const cartStore    = useCartStore()
const { getClassifiers } = useClassifierService()
const localeStore  = useLocaleStore()
const { t }        = useI18n()

const product       = ref(null)
const loading       = ref(true)
const adding        = ref(false)
const quantity      = ref(1)
const classifiers   = ref({})
const showValidation = ref(false)
const featured      = ref([])

// ── Gallery ──────────────────────────────────────────────
const carouselApi = ref(null)
const activeSlide = ref(0)

const images = computed(() => product.value?.media_items ?? [])

function onCarouselInit(api) {
  carouselApi.value = api
  api.on('select', () => { activeSlide.value = api.selectedScrollSnap() })
}

// ── PhotoSwipe ──────────────────────────────────────────
let pswp = null
const imageDimensions = {}

function onImageLoad(e, id) {
  const { naturalWidth: w, naturalHeight: h } = e.target
  if (w && h) imageDimensions[id] = { w, h }
}

function openLightbox(index) {
  pswp?.destroy()
  const alt = localeStore.t(product.value?.name)
  pswp = new PhotoSwipe({
    dataSource: images.value.map(img => ({
      src:  img.original_url,
      msrc: img.thumb_url,
      alt,
      ...(imageDimensions[img.id] ?? { w: 1200, h: 1200 }),
    })),
    index,
    bgOpacity:             0.92,
    showHideAnimationType: 'fade',
    wheelToZoom:           true,
  })
  pswp.init()
}

onUnmounted(() => pswp?.destroy())

// ── Product data ────────────────────────────────────────
const isInCart = computed(() => product.value && cartStore.itemIds.has(product.value.id))

const {
  selectedColor, selectedSize, selectedColorName,
  colors, hasColors, hasSizes, availableSizes,
  isComplete, variant, cartPayload,
  selectColor, selectSize,
} = useVariantPicker(() => product.value?.variants)

const maxQuantity  = computed(() => Math.min(MAX_CART_QUANTITY, variant.value?.stock || MAX_CART_QUANTITY))
watch(maxQuantity, max => { quantity.value = Math.min(quantity.value, max) })

const displayPrice = computed(() => variant.value?.price ?? product.value?.price)

const classifierLabel = (type, key) => {
  const item = (classifiers.value?.[type] ?? []).find(c => c.key === key)
  return item ? localeStore.t(item.name) : key
}

const specs = computed(() => {
  const p = product.value
  if (!p) return []
  return [
    p.code            && { label: t('product.spec.article'),    value: p.code },
    p.season          && { label: t('product.spec.season'),     value: classifierLabel('season', p.season) },
    p.length          && { label: t('product.spec.length'),     value: classifierLabel('length', p.length) },
    p.outer_material  && { label: t('product.spec.outer'),      value: localeStore.t(p.outer_material.name) },
    p.lining_material && { label: t('product.spec.lining'),     value: localeStore.t(p.lining_material.name) },
    p.filling         && { label: t('product.spec.filling'),    value: localeStore.t(p.filling.name) },
    p.hood            && { label: t('product.spec.hood'),       value: t(p.detachable_hood ? 'product.spec.hoodDetach' : 'product.spec.hoodYes') },
    p.waterproof      && { label: t('product.spec.waterproof'), value: t('common.yes') },
  ].filter(Boolean)
})

// ColorSwatches / SizePicker emit null when the active option is clicked again
function toggleColor(hex) {
  selectColor(hex)
  showValidation.value = false
}

function toggleSize(size) {
  selectSize(size)
  showValidation.value = false
}

onMounted(async () => {
  try {
    const [p, cl] = await Promise.all([
      getById(route.params.id),
      getClassifiers(),
    ])
    product.value     = p
    classifiers.value = cl
  } catch (e) {
    console.error('Failed to fetch product:', e)
  } finally {
    loading.value = false
  }

  // Non-blocking — featured carousel doesn't affect the main page
  try {
    const data = await getFeatured()
    const items = Array.isArray(data) ? data : (data?.data ?? [])
    featured.value = items.filter(f => f.id !== product.value?.id)
  } catch (e) {
    console.error('Failed to fetch featured:', e)
  }
})

const addToCart = async () => {
  if (!product.value || adding.value || isInCart.value) return

  if (!isComplete.value) {
    showValidation.value = true
    return
  }

  adding.value = true
  try {
    await cartStore.add(product.value.id, { ...cartPayload.value, quantity: quantity.value })
    toast.success(localeStore.t(product.value.name), {
      description: [selectedSize.value, localeStore.t(variant.value?.color)].filter(Boolean).join(' · ') || undefined,
    })
  } catch (e) {
    console.error('Failed to add to cart:', e)
  } finally {
    adding.value = false
  }
}
</script>

