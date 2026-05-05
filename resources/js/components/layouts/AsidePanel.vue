<template>
  <aside class="hidden xl:block w-64 border-r bg-background sticky top-0 h-screen overflow-y-auto">
    <div class="p-6 space-y-8">
      <!-- Filters Section -->
      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold text-lg">Filters</h3>
          <Button variant="ghost" size="sm" @click="resetFilters" class="text-xs h-6 px-2">
            Clear
          </Button>
        </div>
        <Separator />
      </div>

      <!-- Price Filter -->
      <div class="space-y-4">
        <Accordion type="multiple" :default-value="['price']" class="w-full">
          <AccordionItem value="price" class="border-none">
            <AccordionTrigger class="py-2 px-0 hover:no-underline">
              <span class="font-medium">Price</span>
            </AccordionTrigger>
            <AccordionContent class="pb-2">
              <div class="grid grid-cols-2 gap-2">
                <Input
                    placeholder="From"
                    type="number"
                    class="h-9"
                    v-model="priceRange.min"
                    @input="handlePriceChange"
                />
                <Input
                    placeholder="To"
                    type="number"
                    class="h-9"
                    v-model="priceRange.max"
                    @input="handlePriceChange"
                />
              </div>
            </AccordionContent>
          </AccordionItem>
        </Accordion>
      </div>

      <!-- Categories Filter -->
      <div class="space-y-4">
        <Accordion type="multiple" :default-value="['category']" class="w-full">
          <AccordionItem value="category" class="border-none">
            <AccordionTrigger class="py-2 px-0 hover:no-underline">
              <span class="font-medium">Categories</span>
            </AccordionTrigger>
            <AccordionContent class="pb-2">
              <div class="space-y-2">
                <div v-for="cat in categories" :key="cat.id" class="flex items-center space-x-2">
                  <Checkbox
                      :id="`cat-${cat.id}`"
                      :checked="selectedCategories.includes(cat.id)"
                      @update:checked="handleToggleCategory(cat.id)"
                  />
                  <Label
                      :for="`cat-${cat.id}`"
                      class="text-sm cursor-pointer hover:text-primary transition-colors"
                  >
                    {{ cat.name }}
                  </Label>
                </div>
              </div>
            </AccordionContent>
          </AccordionItem>
        </Accordion>
      </div>

    </div>
  </aside>
</template>

<script setup>
import { onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import {Accordion, AccordionContent, AccordionItem, AccordionTrigger} from '@/components/ui/accordion';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useFilterStore } from '@/store/filterStore.js';
import { storeToRefs } from 'pinia';

const categoryStore = useCategoryStore();
const filterStore = useFilterStore();
const { categories } = storeToRefs(categoryStore);
const { selectedCategories, priceRange } = storeToRefs(filterStore);

onMounted(async () => {
  if (categories.value.length === 0) {
    await categoryStore.load();
  }
});

const handleToggleCategory = (categoryId) => {
  filterStore.toggleCategory(categoryId);
};

const handlePriceChange = () => {
  filterStore.setPriceRange(priceRange.value.min, priceRange.value.max);
};

const resetFilters = () => {
  filterStore.reset();
};
</script>