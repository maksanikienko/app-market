<template>
  <!-- Mobile backdrop -->
  <Teleport to="body">
    <Transition
      enter-active-class="transition-opacity duration-200"
      enter-from-class="opacity-0"
      leave-active-class="transition-opacity duration-200"
      leave-to-class="opacity-0"
    >
      <div
        v-if="filterStore.mobileFilterOpen"
        class="fixed inset-0 z-40 bg-black/50 md:hidden"
        @click="filterStore.closeMobileFilter()"
      />
    </Transition>
  </Teleport>

  <!-- Panel (mobile: fixed drawer, desktop: sticky sidebar) -->
  <aside
    :class="[
      'flex flex-col bg-white overflow-y-auto transition-transform duration-300 ease-out',
      // Mobile: fixed left drawer
      'fixed top-0 bottom-0 left-0 z-50 w-72 shadow-2xl',
      filterStore.mobileFilterOpen ? 'translate-x-0' : '-translate-x-full',
      // Desktop: sticky sidebar (overrides fixed + transform)
      'md:sticky md:bottom-auto md:top-16 md:h-[calc(100vh-4rem)] md:z-auto',
      'md:w-56 md:shadow-none md:border-r md:border-stone-200 md:translate-x-0',
    ]"
  >

    <!-- Mobile header -->
    <div class="md:hidden flex items-center justify-between px-5 py-4 border-b border-stone-200 shrink-0">
      <h3 class="text-sm font-semibold uppercase tracking-widest text-stone-900">{{ t('filter.title') }}</h3>
      <button
        @click="filterStore.closeMobileFilter()"
        class="p-1.5 rounded-lg hover:bg-stone-100 transition-colors"
      >
        <X class="h-4 w-4 text-stone-500" />
      </button>
    </div>

    <!-- Filter content -->
    <div class="px-5 py-5 space-y-1 flex-1">

      <!-- Desktop title + clear -->
      <div class="hidden md:flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold uppercase tracking-widest text-stone-900">{{ t('filter.title') }}</h3>
        <button
          v-if="filterStore.hasAnyFilter"
          @click="filterStore.reset()"
          class="text-[10px] uppercase tracking-wider text-stone-400 hover:text-stone-700 transition-colors"
        >{{ t('filter.clearAll') }}</button>
      </div>

      <Accordion type="multiple" :default-value="['price', 'category']" class="w-full">

        <!-- Price -->
        <AccordionItem value="price" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">
            {{ t('filter.price') }}
          </AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="flex gap-2">
              <input
                :placeholder="t('filter.priceFrom')"
                type="number"
                v-model="filterStore.priceRange.min"
                @change="filterStore.setPriceRange(filterStore.priceRange.min, filterStore.priceRange.max)"
                class="w-full h-8 px-2.5 text-xs rounded-md border border-stone-200 bg-stone-50 placeholder:text-stone-400 focus:outline-none focus:border-stone-400 transition-colors"
              />
              <input
                :placeholder="t('filter.priceTo')"
                type="number"
                v-model="filterStore.priceRange.max"
                @change="filterStore.setPriceRange(filterStore.priceRange.min, filterStore.priceRange.max)"
                class="w-full h-8 px-2.5 text-xs rounded-md border border-stone-200 bg-stone-50 placeholder:text-stone-400 focus:outline-none focus:border-stone-400 transition-colors"
              />
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Category -->
        <AccordionItem value="category" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">
            {{ t('filter.category') }}
          </AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="space-y-2.5">
              <label v-for="cat in categories" :key="cat.id" class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.selectedCategories.includes(cat.id)" @change="filterStore.toggleCategory(cat.id)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ localeStore.t(cat.name) }}</span>
              </label>
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Color -->
        <AccordionItem v-if="variantOptions.colors.length" value="color" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">
            {{ t('filter.color') }}
          </AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="flex flex-wrap gap-2">
              <TooltipProvider v-for="c in variantOptions.colors" :key="c.name">
                <Tooltip>
                  <TooltipTrigger as-child>
                    <button
                        type="button"
                        @click="filterStore.toggleColor(c.name)"
                        :class="[
              'w-6 h-6 rounded-full border-2 transition-all duration-200',
              filterStore.colors.includes(c.name)
                ? 'border-stone-900 scale-110'
                : 'border-stone-200 hover:border-stone-400'
            ]"
                        :style="{ backgroundColor: c.hex }"
                    />
                  </TooltipTrigger>
                  <TooltipContent side="top" class="bg-stone-900 text-white text-xs px-2 py-1">
                    {{ colorLabel(c.name) }}
                  </TooltipContent>
                </Tooltip>
              </TooltipProvider>
            </div>
            <p v-if="filterStore.colors.length" class="text-[10px] text-stone-400 mt-2">
              {{ filterStore.colors.map(c => colorLabel(c)).join(' · ') }}
            </p>
          </AccordionContent>
        </AccordionItem>

        <!-- Size -->
        <AccordionItem v-if="variantOptions.sizes.length" value="size" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">
            {{ t('filter.size') }}
          </AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="flex flex-wrap gap-1.5">
              <button
                v-for="size in variantOptions.sizes" :key="size"
                type="button" @click="filterStore.toggleSize(size)"
                :class="[
                  'min-w-[2rem] px-2 py-1 text-[11px] font-medium border rounded transition-colors duration-150',
                  filterStore.sizes.includes(size) ? 'bg-stone-900 text-white border-stone-900' : 'border-stone-200 text-stone-600 hover:border-stone-900 hover:text-stone-900'
                ]"
              >{{ size }}</button>
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Outer material -->
        <AccordionItem v-if="cls.outer_material?.length" value="outer_material" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">{{ t('filter.outer') }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="space-y-2.5">
              <label v-for="c in cls.outer_material" :key="c.id" class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.outerMaterials.includes(c.id)" @change="filterStore.toggleOuter(c.id)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ localeStore.t(c.name) }}</span>
              </label>
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Lining -->
        <AccordionItem v-if="cls.lining_material?.length" value="lining_material" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">{{ t('filter.lining') }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="space-y-2.5">
              <label v-for="c in cls.lining_material" :key="c.id" class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.liningMaterials.includes(c.id)" @change="filterStore.toggleLining(c.id)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ localeStore.t(c.name) }}</span>
              </label>
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Filling -->
        <AccordionItem v-if="cls.filling?.length" value="filling" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">{{ t('filter.filling') }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="space-y-2.5">
              <label v-for="c in cls.filling" :key="c.id" class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.fillings.includes(c.id)" @change="filterStore.toggleFilling(c.id)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ localeStore.t(c.name) }}</span>
              </label>
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Season -->
        <AccordionItem v-if="cls.season?.length" value="season" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">{{ t('filter.season') }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="space-y-2.5">
              <label v-for="c in cls.season" :key="c.key" class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.seasons.includes(c.key)" @change="filterStore.toggleSeason(c.key)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ localeStore.t(c.name) }}</span>
              </label>
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Length -->
        <AccordionItem v-if="clsLength.length" value="length" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">{{ t('filter.length') }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="space-y-2.5">
              <label v-for="c in clsLength" :key="c.key" class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.lengths.includes(c.key)" @change="filterStore.toggleLength(c.key)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ localeStore.t(c.name) }}</span>
              </label>
            </div>
          </AccordionContent>
        </AccordionItem>

        <!-- Features -->
        <AccordionItem value="features" class="border-none">
          <AccordionTrigger class="py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900">{{ t('filter.features') }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="space-y-2.5">
              <label class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.hood === true" @change="filterStore.setHood(true)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ t('filter.hood') }}</span>
              </label>
              <label class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="filterStore.waterproof === true" @change="filterStore.setWaterproof(true)" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ t('filter.waterproof') }}</span>
              </label>
            </div>
          </AccordionContent>
        </AccordionItem>

      </Accordion>
    </div>

    <!-- Mobile footer: apply + clear -->
    <div class="md:hidden shrink-0 px-5 py-4 border-t border-stone-200 flex gap-2">
      <button
        @click="filterStore.closeMobileFilter()"
        class="flex-1 h-10 bg-stone-900 text-white text-xs font-semibold uppercase tracking-widest rounded-lg transition-colors hover:bg-stone-700"
      >
        {{ t('filter.apply') }}
      </button>
      <button
        v-if="filterStore.hasAnyFilter"
        @click="filterStore.reset()"
        class="h-10 px-4 border border-stone-200 text-stone-500 text-xs rounded-lg hover:bg-stone-50 transition-colors"
      >
        {{ t('filter.clearAll') }}
      </button>
    </div>

  </aside>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { X } from 'lucide-vue-next';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useFilterStore } from '@/store/filterStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useClassifierService } from '@/services/classifierService.js';
import { useI18n } from '@/i18n';
import { storeToRefs } from 'pinia';

const categoryStore = useCategoryStore();
const filterStore   = useFilterStore();
const localeStore   = useLocaleStore();
const { getClassifiers } = useClassifierService();
const { t } = useI18n();

const { categories } = storeToRefs(categoryStore);
const cls            = ref({});
const variantOptions = ref({ colors: [], sizes: [] });

const clsLength = computed(() => cls.value?.length ?? []);

function colorLabel(nameJson) {
  if (!nameJson) return ''
  try {
    const obj = typeof nameJson === 'string' ? JSON.parse(nameJson) : nameJson
    return localeStore.t(obj)
  } catch {
    return nameJson
  }
}

onMounted(async () => {
  const [, classifiers, vOpts] = await Promise.all([
    categories.value.length === 0 ? categoryStore.load() : Promise.resolve(null),
    getClassifiers(),
    axios.get('/api/products/variant-options').then(r => r.data),
  ]);
  if (classifiers) cls.value = classifiers;
  variantOptions.value = vOpts ?? { colors: [], sizes: [] };
});
</script>