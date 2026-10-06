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
    class="select-none"
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
        <h3 class="text-sm font-semibold uppercase tracking-widest text-stone-900 cursor-default">{{ t('filter.title') }}</h3>
        <button
          v-if="filterStore.hasAnyFilter"
          @click="filterStore.reset()"
          class="text-[10px] uppercase tracking-wider text-stone-400 hover:text-stone-700 transition-colors"
        >{{ t('filter.clearAll') }}</button>
      </div>

      <Accordion type="multiple" :default-value="['price', 'category']" class="w-full">

        <!-- Price -->
        <AccordionItem value="price" class="border-none">
          <AccordionTrigger :class="TRIGGER_CLASS">{{ t('filter.price') }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div class="flex gap-2">
              <input
                :placeholder="t('filter.priceFrom')"
                type="number"
                v-model="filterStore.priceRange.min"
                @change="applyPrice"
                class="w-full h-8 px-2.5 text-xs rounded-md border border-stone-200 bg-stone-50 placeholder:text-stone-400 focus:outline-none focus:border-stone-400 transition-colors"
              />
              <input
                :placeholder="t('filter.priceTo')"
                type="number"
                v-model="filterStore.priceRange.max"
                @change="applyPrice"
                class="w-full h-8 px-2.5 text-xs rounded-md border border-stone-200 bg-stone-50 placeholder:text-stone-400 focus:outline-none focus:border-stone-400 transition-colors"
              />
            </div>
          </AccordionContent>
        </AccordionItem>

        <AccordionItem
          v-for="group in checkboxGroups"
          :key="group.value"
          :value="group.value"
          class="border-none"
        >
          <AccordionTrigger :class="TRIGGER_CLASS">{{ t(group.title) }}</AccordionTrigger>
          <AccordionContent class="pb-4">
            <div v-if="group.value === 'color'" class="flex flex-wrap gap-2">
              <TooltipProvider v-for="c in variantOptions.colors" :key="c.name">
                <Tooltip>
                  <TooltipTrigger as-child>
                    <button
                      type="button"
                      @click="filterStore.toggle('colors', c.name)"
                      :class="[
                        'w-6 h-6 rounded-full border-2 transition-all duration-200',
                        filterStore.colors.includes(c.name) ? 'border-stone-900 scale-110' : 'border-stone-200 hover:border-stone-400'
                      ]"
                      :style="{ backgroundColor: c.hex }"
                    />
                  </TooltipTrigger>
                  <TooltipContent side="top" class="bg-stone-900 text-white text-xs px-2 py-1">
                    {{ localeStore.t(c.name) }}
                  </TooltipContent>
                </Tooltip>
              </TooltipProvider>
              <p v-if="filterStore.colors.length" class="w-full text-[10px] text-stone-400">
                {{ filterStore.colors.map(localeStore.t).join(' · ') }}
              </p>
            </div>

            <div v-else-if="group.value === 'size'" class="flex flex-wrap gap-1.5">
              <button
                v-for="size in variantOptions.sizes" :key="size"
                type="button" @click="filterStore.toggle('sizes', size)"
                :class="[
                  'min-w-[2rem] px-2 py-1 text-[11px] font-medium border rounded transition-colors duration-150',
                  filterStore.sizes.includes(size) ? 'bg-stone-900 text-white border-stone-900' : 'border-stone-200 text-stone-600 hover:border-stone-900 hover:text-stone-900'
                ]"
              >{{ size }}</button>
            </div>

            <div v-else class="space-y-2.5">
              <label v-for="item in group.items" :key="item.value" class="flex items-center gap-2.5 cursor-pointer group/item">
                <input type="checkbox" :checked="item.checked" @change="item.toggle()" class="h-3.5 w-3.5 rounded-sm border-stone-300 accent-stone-900 cursor-pointer" />
                <span class="text-xs text-stone-600 group-hover/item:text-stone-900 transition-colors">{{ item.label }}</span>
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
import { storeToRefs } from 'pinia';
import { X } from 'lucide-vue-next';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useFilterStore } from '@/store/filterStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useClassifierService } from '@/services/classifierService.js';
import { useProductService } from '@/services/productService.js';
import { useI18n } from '@/i18n';

const TRIGGER_CLASS = 'py-3 px-0 hover:no-underline text-xs font-semibold uppercase tracking-widest text-stone-600 hover:text-stone-900';

const categoryStore = useCategoryStore();
const filterStore   = useFilterStore();
const localeStore   = useLocaleStore();
const { getClassifiers }    = useClassifierService();
const { getVariantOptions } = useProductService();
const { t } = useI18n();

const { categories } = storeToRefs(categoryStore);
const classifiers    = ref({});
const variantOptions = ref({ colors: [], sizes: [] });

// Builds a checkbox list bound to a filterStore array; `idKey` is 'id' for DB classifiers, 'key' for enums
const listGroup = (value, title, items, storeKey, idKey = 'id') => ({
  value,
  title,
  items: (items ?? []).map(item => ({
    value:   item[idKey],
    label:   localeStore.t(item.name),
    checked: filterStore[storeKey].includes(item[idKey]),
    toggle:  () => filterStore.toggle(storeKey, item[idKey]),
  })),
});

const flagItem = (key, label) => ({
  value:   key,
  label:   t(label),
  checked: filterStore[key] === true,
  toggle:  () => filterStore.toggleFlag(key),
});

const checkboxGroups = computed(() => {
  const cls = classifiers.value;
  return [
    listGroup('category', 'filter.category', categories.value, 'selectedCategories'),
    variantOptions.value.colors.length && { value: 'color', title: 'filter.color' },
    variantOptions.value.sizes.length  && { value: 'size',  title: 'filter.size' },
    listGroup('outer_material',  'filter.outer',  cls.outer_material,  'outerMaterials'),
    listGroup('lining_material', 'filter.lining', cls.lining_material, 'liningMaterials'),
    listGroup('filling',         'filter.filling', cls.filling,        'fillings'),
    listGroup('season',          'filter.season', cls.season,          'seasons', 'key'),
    listGroup('length',          'filter.length', cls.length,          'lengths', 'key'),
    { value: 'features', title: 'filter.features', items: [flagItem('hood', 'filter.hood'), flagItem('waterproof', 'filter.waterproof')] },
  ].filter(group => group && (!group.items || group.items.length));
});

const applyPrice = () => filterStore.setPriceRange(filterStore.priceRange.min, filterStore.priceRange.max);

onMounted(async () => {
  const [, cls, options] = await Promise.all([
    categoryStore.load(),
    getClassifiers(),
    getVariantOptions(),
  ]);
  classifiers.value    = cls ?? {};
  variantOptions.value = options ?? { colors: [], sizes: [] };
});
</script>
