<template>
  <div class="flex gap-4 border rounded-lg p-4">
    <img :src="item.image" :alt="item.name" class="h-24 w-24 rounded-lg object-cover" />

    <div class="flex-1 space-y-2">
      <div class="flex justify-between items-start">
        <div>
          <h3 class="font-semibold">{{ item.name }}</h3>
          <p class="text-sm text-muted-foreground">${{ item.price.toFixed(2) }}</p>
        </div>
        <Button variant="ghost" size="sm" @click="$emit('remove', item.id)" class="text-destructive hover:text-destructive">
          <Trash2 class="h-4 w-4" />
        </Button>
      </div>

      <div class="flex items-center justify-between pt-2">
        <span class="text-lg font-bold">${{ (item.price * item.quantity).toFixed(2) }}</span>
        <div class="flex items-center border rounded">
          <Button variant="ghost" size="sm" class="px-2" @click="$emit('update-quantity', item.id, Math.max(1, item.quantity - 1))">
            <Minus class="h-4 w-4" />
          </Button>
          <span class="w-8 text-center text-sm">{{ item.quantity }}</span>
          <Button variant="ghost" size="sm" class="px-2" @click="$emit('update-quantity', item.id, item.quantity + 1)">
            <Plus class="h-4 w-4" />
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import { Trash2, Minus, Plus } from 'lucide-vue-next';

defineProps({
  item: {
    type: Object,
    required: true
  }
});

defineEmits(['remove', 'update-quantity']);
</script>
