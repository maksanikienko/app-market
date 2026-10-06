<template>
  <ToggleGroup
    type="single"
    variant="outline"
    :spacing="1"
    class="flex-wrap justify-start gap-2"
    :model-value="modelValue ?? undefined"
    @update:model-value="emit('update:modelValue', $event || null)"
  >
    <ToggleGroupItem
      v-for="size in sizes"
      :key="size"
      :value="size"
      class="h-10 min-w-12 rounded-xl px-3 text-sm font-medium transition-all duration-200 hover:border-foreground/40 data-[state=on]:border-primary data-[state=on]:bg-primary data-[state=on]:text-primary-foreground"
    >{{ size }}</ToggleGroupItem>
  </ToggleGroup>
</template>

<script setup>
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';

defineProps({
  sizes:      { type: Array, required: true },
  modelValue: { type: [String, Number], default: null },
});
const emit = defineEmits(['update:modelValue']);
</script>
