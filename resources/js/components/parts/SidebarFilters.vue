<template>
  <div>
    <SidebarSeparator class="mx-0" />

    <SidebarGroup class="pb-0">
      <div class="flex items-center justify-between px-2 h-8">
        <span class="flex items-center gap-2 text-xs font-medium text-sidebar-foreground/70">
          <SlidersHorizontal class="size-3.5" />
          {{ t('filter.title') }}
          <Badge v-if="filterStore.hasAnyFilter" :key="filterStore.activeCount" class="animate-pop h-5 min-w-5 justify-center rounded-full bg-brand px-1.5 text-[10px] text-brand-foreground border-0">
            {{ filterStore.activeCount }}
          </Badge>
        </span>
        <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-x-2" leave-active-class="transition duration-200" leave-to-class="opacity-0">
          <button
            v-if="filterStore.hasAnyFilter"
            class="text-xs text-muted-foreground hover:text-brand transition-colors"
            @click="filterStore.reset()"
          >{{ t('filter.clearAll') }}</button>
        </Transition>
      </div>
    </SidebarGroup>

    <!-- Price -->
    <FilterSection :title="t('filter.price')" default-open>
      <div class="flex items-center gap-2">
        <SidebarInput
          v-model="filterStore.priceRange.min"
          type="number"
          min="0"
          :placeholder="t('filter.priceFrom')"
          class="h-9 bg-background"
          @change="applyPrice"
        />
        <span class="text-muted-foreground">—</span>
        <SidebarInput
          v-model="filterStore.priceRange.max"
          type="number"
          min="0"
          :placeholder="t('filter.priceTo')"
          class="h-9 bg-background"
          @change="applyPrice"
        />
      </div>
    </FilterSection>

    <!-- Colors -->
    <FilterSection v-if="variantOptions.colors.length" :title="t('filter.color')" default-open>
      <div class="flex flex-wrap gap-2">
        <Tooltip v-for="c in variantOptions.colors" :key="c.name">
          <TooltipTrigger as-child>
            <button
              type="button"
              :aria-label="localeStore.t(c.name)"
              :aria-pressed="filterStore.colors.includes(c.name)"
              class="relative size-7 rounded-full ring-1 ring-black/10 transition-all duration-300 ease-spring hover:scale-110"
              :class="filterStore.colors.includes(c.name) && 'ring-2 ring-offset-2 ring-offset-sidebar ring-brand scale-110'"
              :style="{ backgroundColor: c.hex }"
              @click="filterStore.toggle('colors', c.name)"
            >
              <Check v-if="filterStore.colors.includes(c.name)" class="absolute inset-0 m-auto size-3.5 text-white mix-blend-difference animate-pop" />
            </button>
          </TooltipTrigger>
          <TooltipContent side="top">{{ localeStore.t(c.name) }}</TooltipContent>
        </Tooltip>
      </div>
    </FilterSection>

    <!-- Sizes -->
    <FilterSection v-if="variantOptions.sizes.length" :title="t('filter.size')" default-open>
      <ToggleGroup
        type="multiple"
        variant="outline"
        size="sm"
        :spacing="1"
        class="flex-wrap justify-start gap-1.5"
        :model-value="filterStore.sizes"
        @update:model-value="filterStore.sizes = $event"
      >
        <ToggleGroupItem
          v-for="size in variantOptions.sizes"
          :key="size"
          :value="size"
          class="min-w-10 rounded-lg bg-background text-xs data-[state=on]:bg-primary data-[state=on]:text-primary-foreground data-[state=on]:border-primary"
        >{{ size }}</ToggleGroupItem>
      </ToggleGroup>
    </FilterSection>

    <!-- Classifier checklists -->
    <FilterSection v-for="group in checkboxGroups" :key="group.value" :title="t(group.title)">
      <div class="space-y-1">
        <label
          v-for="item in group.items"
          :key="item.value"
          class="flex items-center gap-2.5 rounded-lg px-1.5 py-1.5 cursor-pointer text-sm text-sidebar-foreground/80 transition-colors hover:bg-sidebar-accent hover:text-sidebar-foreground"
        >
          <Checkbox :model-value="item.checked" class="bg-background data-[state=checked]:bg-brand data-[state=checked]:border-brand" @update:model-value="item.toggle()" />
          <span class="truncate">{{ item.label }}</span>
        </label>
      </div>
    </FilterSection>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { SlidersHorizontal, Check } from 'lucide-vue-next';
import { SidebarGroup, SidebarInput, SidebarSeparator } from '@/components/ui/sidebar';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import FilterSection from '@/components/parts/FilterSection.vue';
import { useFilterStore } from '@/store/filterStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useClassifierService } from '@/services/classifierService.js';
import { useProductService } from '@/services/productService.js';
import { useI18n } from '@/i18n';

const filterStore = useFilterStore();
const localeStore = useLocaleStore();
const { getClassifiers }    = useClassifierService();
const { getVariantOptions } = useProductService();
const { t } = useI18n();

const classifiers    = ref({});
const variantOptions = ref({ colors: [], sizes: [] });

// Checkbox list bound to a filterStore array; `idKey` is 'id' for DB classifiers, 'key' for enums
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
    listGroup('outer_material',  'filter.outer',   cls.outer_material,  'outerMaterials'),
    listGroup('lining_material', 'filter.lining',  cls.lining_material, 'liningMaterials'),
    listGroup('filling',         'filter.filling', cls.filling,         'fillings'),
    listGroup('season',          'filter.season',  cls.season,          'seasons', 'key'),
    listGroup('length',          'filter.length',  cls.length,          'lengths', 'key'),
    { value: 'features', title: 'filter.features', items: [flagItem('hood', 'filter.hood'), flagItem('waterproof', 'filter.waterproof')] },
  ].filter(group => group.items.length);
});

const applyPrice = () => filterStore.setPriceRange(filterStore.priceRange.min, filterStore.priceRange.max);

onMounted(async () => {
  const [cls, options] = await Promise.all([getClassifiers(), getVariantOptions()]);
  classifiers.value    = cls ?? {};
  variantOptions.value = options ?? { colors: [], sizes: [] };
});
</script>
