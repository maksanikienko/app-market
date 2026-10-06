<template>
  <div class="inline-flex items-center rounded-full border bg-card" :class="size === 'lg' ? 'h-12 p-1' : 'h-9 p-0.5'">
    <Button variant="ghost" size="icon" class="rounded-full" :class="BUTTON[size]" :disabled="modelValue <= min" @click="emit('update:modelValue', modelValue - 1)">
      <Minus />
    </Button>
    <span class="relative w-9 overflow-hidden text-center text-sm font-semibold tabular-nums">
      <Transition mode="out-in" enter-active-class="transition duration-200 ease-out-expo" enter-from-class="opacity-0 translate-y-2" leave-active-class="transition duration-100" leave-to-class="opacity-0 -translate-y-2">
        <span :key="modelValue" class="inline-block">{{ modelValue }}</span>
      </Transition>
    </span>
    <Button variant="ghost" size="icon" class="rounded-full" :class="BUTTON[size]" :disabled="modelValue >= max" @click="emit('update:modelValue', modelValue + 1)">
      <Plus />
    </Button>
  </div>
</template>

<script setup>
import { Minus, Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const BUTTON = { md: 'size-8', lg: 'size-10' };

defineProps({
  modelValue: { type: Number, required: true },
  min:        { type: Number, default: 1 },
  max:        { type: Number, default: Infinity },
  size:       { type: String, default: 'md' },
});
const emit = defineEmits(['update:modelValue']);
</script>
