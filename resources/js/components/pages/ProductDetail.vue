<template>
  <div v-if="loading" class="space-y-8">
    <Skeleton class="h-5 w-48" />
    <div class="grid gap-8 lg:grid-cols-2">
      <Skeleton class="aspect-square rounded-xl" />
      <div class="space-y-4">
        <Skeleton class="h-8 w-3/4" />
        <Skeleton class="h-5 w-1/2" />
        <Skeleton class="h-24 w-full" />
        <Skeleton class="h-12 w-full" />
      </div>
    </div>
  </div>

  <div v-else-if="product" class="space-y-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm">
      <RouterLink to="/products" class="text-muted-foreground hover:text-foreground transition-colors">
        {{ t('products.title') }}
      </RouterLink>
      <span class="text-muted-foreground">/</span>
      <span>{{ localeStore.t(product.name) }}</span>
    </div>

    <div class="grid gap-10 lg:grid-cols-2">

      <!-- ── Gallery ─────────────────────────────────────── -->
      <div class="space-y-3">

        <!-- Main Carousel -->
        <div class="relative group rounded-xl overflow-hidden bg-stone-100 select-none">
          <template v-if="images.length">
            <Carousel
              :opts="{ loop: images.length > 1 }"
              class="w-full"
              @init-api="onCarouselInit"
            >
              <CarouselContent class="-ml-0">
                <CarouselItem
                  v-for="(img, i) in images"
                  :key="img.id"
                  class="pl-0"
                >
                  <div class="aspect-[4/5] sm:aspect-square">
                    <img
                      :src="img.original_url"
                      :alt="localeStore.t(product.name)"
                      loading="lazy"
                      class="w-full h-full object-cover cursor-zoom-in"
                      @click="openLightbox(i)"
                      @load="onImageLoad($event, img.id)"
                    />
                  </div>
                </CarouselItem>
              </CarouselContent>

              <template v-if="images.length > 1">
                <CarouselPrevious class="left-3 bg-white/80 backdrop-blur-sm hover:bg-white border-0 shadow-sm opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity" />
                <CarouselNext    class="right-3 bg-white/80 backdrop-blur-sm hover:bg-white border-0 shadow-sm opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity" />
              </template>

              <!-- Counter -->
              <div
                v-if="images.length > 1"
                class="absolute bottom-3 right-3 z-10 text-xs font-medium bg-black/40 text-white px-2.5 py-1 rounded-full pointer-events-none"
              >
                {{ activeSlide + 1 }}/{{ images.length }}
              </div>

            </Carousel>
          </template>

          <div v-else class="aspect-[4/5] sm:aspect-square flex items-center justify-center">
            <Package class="h-16 w-16 text-stone-300" />
          </div>
        </div>

        <!-- Thumbnails -->
        <div v-if="images.length > 1" class="grid grid-cols-5 gap-2">
          <button
            v-for="(img, i) in images"
            :key="img.id"
            @click="carouselApi?.scrollTo(i)"
            :class="[
              'aspect-square rounded-lg overflow-hidden transition-all ring-offset-1',
              i === activeSlide ? 'ring-2 ring-stone-900' : 'opacity-50 hover:opacity-100'
            ]"
          >
            <img :src="img.thumb_url" :alt="localeStore.t(product.name)" loading="lazy" class="w-full h-full object-cover" />
          </button>
        </div>

      </div>

      <!-- ── Product Details ──────────────────────────────── -->
      <div class="space-y-6">
        <div>
          <div class="flex items-center gap-2">
            <Badge v-if="product.is_new"  class="bg-blue-500/50  text-white">{{ t('product.badge.new') }}</Badge>
            <Badge v-if="product.is_hit"  class="bg-amber-500/50 text-white">{{ t('product.badge.hit') }}</Badge>
            <Badge v-if="product.is_sale" class="bg-rose-500/50  text-white">{{ t('product.badge.sale') }}</Badge>
          </div>
          <h1 class="text-3xl font-bold mt-1">{{ localeStore.t(product.name) }}</h1>
          <p v-if="product.short_description" class="text-muted-foreground mt-2">
            {{ localeStore.t(product.short_description) }}
          </p>
        </div>

        <!-- Price -->
        <div class="border-y py-4 flex items-baseline gap-3">
          <span class="text-3xl font-bold">{{ Number(displayPrice).toFixed(2) }} lei</span>
          <span v-if="product.old_price" class="text-xl line-through text-muted-foreground">
            {{ Number(product.old_price).toFixed(2) }} lei
          </span>
          <span v-if="product.old_price" class="text-sm font-medium text-red-500">
            -{{ Math.round((1 - product.price / product.old_price) * 100) }}%
          </span>
        </div>

        <!-- Variant selector -->
        <div v-if="hasColors || hasSizes" class="space-y-4">
          <div v-if="hasColors" class="space-y-2">
            <p class="text-sm font-medium" :class="showValidation && !selectedColor ? 'text-destructive' : ''">
              {{ t('product.color') }}:
              <span class="font-normal" :class="selectedColor ? 'text-foreground' : 'text-muted-foreground'">
                {{ selectedColor ? colorLabel(selectedColor) : '—' }}
              </span>
            </p>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="c in uniqueColors" :key="c.name"
                type="button"
                :title="colorLabel(c.name)"
                @click="selectColor(c.name)"
                :class="[
                  'w-8 h-8 rounded-full border-2 transition-all',
                  selectedColor === c.name
                    ? 'border-foreground scale-110 shadow-md'
                    : 'border-transparent hover:scale-105 hover:border-muted-foreground'
                ]"
                :style="{ backgroundColor: c.hex }"
              />
            </div>
          </div>

          <div v-if="hasSizes" class="space-y-2">
            <p class="text-sm font-medium" :class="showValidation && !selectedSize ? 'text-destructive' : ''">
              {{ t('product.size') }}:
            </p>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="size in availableSizes" :key="size"
                type="button"
                @click="selectedSize = selectedSize === size ? null : size; showValidation = false"
                :class="[
                  'min-w-[2.5rem] px-3 py-1.5 text-sm border rounded-md transition-colors',
                  selectedSize === size
                    ? 'bg-primary text-primary-foreground border-primary'
                    : 'hover:border-foreground'
                ]"
              >{{ size }}</button>
            </div>
          </div>

          <p v-if="showValidation && (hasColors && !selectedColor || hasSizes && !selectedSize)"
             class="text-sm text-destructive">
            {{ t('product.choose_params') }}
          </p>

          <p
            v-if="selectedVariant"
            class="text-sm font-medium"
            :class="selectedVariant.stock > 0 ? 'text-emerald-600' : 'text-destructive'"
          >
            {{ selectedVariant.stock > 0
              ? t('product.in_stock') + ': ' + selectedVariant.stock + ' ' + t('product.pcs')
              : t('product.not_in_stock') }}
          </p>
        </div>

        <!-- Quantity + Add to cart -->
        <div class="space-y-3">
          <div class="flex items-center gap-4">
            <span class="text-sm font-medium">{{ t('product.qty') }}</span>
            <div class="flex items-center border rounded-lg">
              <Button variant="ghost" size="sm" class="px-3" @click="quantity = Math.max(1, quantity - 1)">
                <Minus class="h-4 w-4" />
              </Button>
              <span class="w-12 text-center text-sm font-medium">{{ quantity }}</span>
              <Button variant="ghost" size="sm" class="px-3" @click="quantity++">
                <Plus class="h-4 w-4" />
              </Button>
            </div>
          </div>

          <div class="flex gap-3">
            <Button size="lg" class="flex-1" @click="addToCart"
              :disabled="adding || isInCart || selectedVariant?.stock === 0"
              :variant="isInCart ? 'secondary' : 'default'">
              <Check v-if="isInCart" class="h-5 w-5 mr-2" />
              <ShoppingCart v-else class="h-5 w-5 mr-2" />
              {{ adding ? t('product.adding') : isInCart ? t('product.added') : t('product.addToCart') }}
            </Button>
            <Button variant="outline" size="lg" class="px-6">
              <Heart class="h-5 w-5" />
            </Button>
          </div>
        </div>

        <!-- Specs -->
        <div class="border-t pt-4 space-y-2 text-sm">
          <div v-if="product.code" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.article') }}</span>
            <span class="font-medium">{{ product.code }}</span>
          </div>
          <div v-if="product.season" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.season') }}</span>
            <span class="font-medium">{{ seasonLabel }}</span>
          </div>
          <div v-if="product.length" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.length') }}</span>
            <span class="font-medium">{{ lengthLabel }}</span>
          </div>
          <div v-if="product.outer_material" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.outer') }}</span>
            <span class="font-medium">{{ localeStore.t(product.outer_material.name) }}</span>
          </div>
          <div v-if="product.lining_material" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.lining') }}</span>
            <span class="font-medium">{{ localeStore.t(product.lining_material.name) }}</span>
          </div>
          <div v-if="product.filling" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.filling') }}</span>
            <span class="font-medium">{{ localeStore.t(product.filling.name) }}</span>
          </div>
          <div v-if="product.hood" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.hood') }}</span>
            <span class="font-medium">{{ product.detachable_hood ? t('product.spec.hoodDetach') : t('product.spec.hoodYes') }}</span>
          </div>
          <div v-if="product.waterproof" class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.waterproof') }}</span>
            <span class="font-medium">{{ t('common.yes') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-muted-foreground">{{ t('product.spec.delivery') }}</span>
            <span class="font-medium">{{ t('product.spec.days') }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Featured carousel ────────────────────────────── -->
    <section v-if="featured.length" class="border-t pt-10 space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold tracking-tight text-stone-900">
          {{ t('home.arrivals.title') }}
        </h2>
        <RouterLink to="/products" class="text-[10px] uppercase tracking-widest font-medium text-stone-400 hover:text-stone-700 transition-colors">
          {{ t('home.cats.viewAll') }}
        </RouterLink>
      </div>

      <Swiper
        :modules="[Autoplay]"
        :autoplay="{ delay: 3000, disableOnInteraction: false, pauseOnMouseEnter: true }"
        :loop="true"
        :slides-per-view="2.2"
        :space-between="16"
        :breakpoints="{
          640:  { slidesPerView: 3,   spaceBetween: 16 },
          1024: { slidesPerView: 4,   spaceBetween: 20 },
          1280: { slidesPerView: 5,   spaceBetween: 20 },
        }"
      >
        <SwiperSlide v-for="p in featured" :key="p.id" class="!h-auto pb-1">
          <ProductCard :product="p" />
        </SwiperSlide>
      </Swiper>
    </section>
  </div>

  <!-- Not found -->
  <div v-else class="flex h-64 items-center justify-center rounded-lg border border-dashed">
    <div class="text-center space-y-2">
      <Package class="h-12 w-12 mx-auto text-muted-foreground" />
      <p class="font-medium">{{ t('product.notFound') }}</p>
      <RouterLink to="/products">
        <Button variant="outline" size="sm">{{ t('product.backTo') }}</Button>
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import 'swiper/css'
import 'photoswipe/style.css'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay } from 'swiper/modules'
import PhotoSwipe from 'photoswipe'
import { Button } from '@/components/ui/button'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import ProductCard from '@/components/parts/ProductCard.vue'
import { Heart, Minus, Plus, ShoppingCart, Package, Check } from 'lucide-vue-next'
import { useProductService } from '@/services/productService.js'
import { useCartStore } from '@/store/cartStore.js'
import { useClassifierService } from '@/services/classifierService.js'
import { useLocaleStore } from '@/store/localeStore.js'
import { useI18n } from '@/i18n'
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
const selectedColor = ref(null)
const selectedSize  = ref(null)
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

const uniqueColors = computed(() => {
  const seen = new Set()
  return (product.value?.variants ?? []).reduce((acc, v) => {
    if (v.color && !seen.has(v.color)) {
      seen.add(v.color)
      acc.push({ name: v.color, hex: v.color_hex ?? '#aaaaaa' })
    }
    return acc
  }, [])
})

const hasColors = computed(() => uniqueColors.value.length > 0)
const hasSizes  = computed(() => (product.value?.variants ?? []).some(v => v.size))

const availableSizes = computed(() => {
  const variants = product.value?.variants ?? []
  const pool = selectedColor.value ? variants.filter(v => v.color === selectedColor.value) : variants
  const seen = new Set()
  return pool.filter(v => v.size && !seen.has(v.size) && seen.add(v.size)).map(v => v.size)
})

const selectedVariant = computed(() => {
  if (!selectedColor.value && !selectedSize.value) return null
  return (product.value?.variants ?? []).find(v =>
    (!selectedColor.value || v.color === selectedColor.value) &&
    (!selectedSize.value  || v.size  === selectedSize.value)
  ) ?? null
})

const displayPrice = computed(() =>
  selectedVariant.value?.price != null ? selectedVariant.value.price : product.value?.price
)

const seasonLabel = computed(() => {
  const key = product.value?.season
  if (!key) return ''
  const item = (classifiers.value?.season ?? []).find(c => c.key === key)
  return item ? localeStore.t(item.name) : key
})

const lengthLabel = computed(() => {
  const key = product.value?.length
  if (!key) return ''
  const item = (classifiers.value?.length ?? []).find(c => c.key === key)
  return item ? localeStore.t(item.name) : key
})

function colorLabel(nameJson) {
  if (!nameJson) return ''
  try {
    const obj = typeof nameJson === 'string' ? JSON.parse(nameJson) : nameJson
    return localeStore.t(obj)
  } catch { return nameJson }
}

function selectColor(name) {
  selectedColor.value = selectedColor.value === name ? null : name
  showValidation.value = false
  if (selectedSize.value && !availableSizes.value.includes(selectedSize.value)) {
    selectedSize.value = null
  }
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

  if ((hasColors.value && !selectedColor.value) || (hasSizes.value && !selectedSize.value)) {
    showValidation.value = true
    return
  }

  adding.value = true
  try {
    await cartStore.add(product.value.id, {
      variant_id: selectedVariant.value?.id       ?? null,
      color:      selectedVariant.value?.color     ?? null,
      color_hex:  selectedVariant.value?.color_hex ?? null,
      size:       selectedVariant.value?.size      ?? null,
    })
    toast.success(localeStore.t(product.value.name), {
      description: [selectedSize.value, colorLabel(selectedVariant.value?.color)].filter(Boolean).join(' · ') || undefined,
    })
  } catch (e) {
    console.error('Failed to add to cart:', e)
  } finally {
    adding.value = false
  }
}
</script>

