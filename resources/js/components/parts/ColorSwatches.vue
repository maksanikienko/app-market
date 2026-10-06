<template>
  <div class="flex flex-wrap gap-2.5" role="radiogroup">
    <Tooltip v-for="c in colors" :key="c.hex">
      <TooltipTrigger as-child>
        <button
          type="button"
          role="radio"
          :aria-checked="modelValue === c.hex"
          :aria-label="localeStore.t(c.name)"
          class="relative rounded-full ring-1 ring-black/10 transition-all duration-300 ease-spring hover:scale-110"
          :class="[SIZES[size], modelValue === c.hex && 'scale-110 ring-2 ring-brand ring-offset-2 ring-offset-background']"
          :style="{ backgroundColor: c.hex }"
          @click="emit('update:modelValue', modelValue === c.hex ? null : c.hex)"
        >
          <Check v-if="modelValue === c.hex" class="animate-pop absolute inset-0 m-auto size-4 text-white mix-blend-difference" />
        </button>
      </TooltipTrigger>
      <TooltipContent side="top">{{ localeStore.t(c.name) }}</TooltipContent>
    </Tooltip>
  </div>
</template>

<script setup>
import { Check } from 'lucide-vue-next';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import { useLocaleStore } from '@/store/localeStore.js';

const SIZES = { md: 'size-8', lg: 'size-10' };

defineProps({
  colors:     { type: Array, required: true },
  modelValue: { type: String, default: null },
  size:       { type: String, default: 'md' },
});
const emit = defineEmits(['update:modelValue']);

const localeStore = useLocaleStore();
</script>
