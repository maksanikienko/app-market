<template>
  <div class="space-y-8 select-none">
    <div class="flex items-end justify-between gap-4">
      <h1 class="font-display text-4xl md:text-5xl font-semibold leading-none tracking-tight">{{ t('cart.title') }}</h1>
      <span v-if="cartStore.count && !orderPlaced" class="text-sm text-muted-foreground">
        <span class="font-semibold text-foreground tabular-nums">{{ cartStore.count }}</span> {{ t('cart.items') }}
      </span>
    </div>

    <Transition mode="out-in" enter-active-class="transition duration-500 ease-out-expo" enter-from-class="opacity-0 scale-95" leave-active-class="transition duration-200" leave-to-class="opacity-0">
      <!-- Success screen -->
      <div v-if="orderPlaced" key="placed" class="flex flex-col items-center justify-center gap-6 rounded-3xl bg-muted/60 py-20 text-center">
        <div class="relative grid size-20 place-items-center">
          <span class="absolute inset-0 animate-ping rounded-full bg-success/25" />
          <span class="relative grid size-20 place-items-center rounded-full bg-success/15">
            <CheckCircle class="animate-pop size-10 text-success" />
          </span>
        </div>
        <div class="space-y-2">
          <h2 class="font-display text-3xl font-semibold">{{ t('cart.placed.title') }}</h2>
          <p class="max-w-md text-muted-foreground">{{ orderSuccessMsg }}</p>
        </div>
        <Button size="lg" class="rounded-full" as-child>
          <RouterLink :to="{ name: 'products' }">{{ t('cart.continue') }} <ArrowRight /></RouterLink>
        </Button>
      </div>

      <!-- Cart content -->
      <div v-else-if="cartStore.items.length" key="items" class="grid gap-8 lg:grid-cols-[1fr_380px]">
        <TransitionGroup name="list" tag="div" class="relative space-y-3">
          <CartItem
            v-for="item in cartStore.items"
            :key="item.id"
            :item="item"
            class="w-full"
            @remove="removeFromCart"
            @update-quantity="updateQuantity"
          />
        </TransitionGroup>

        <!-- Summary -->
        <Card class="h-fit rounded-3xl p-6 shadow-none lg:sticky lg:top-24">
          <h2 class="font-display text-2xl font-semibold">{{ t('cart.summary') }}</h2>

          <dl class="mt-5 space-y-3 border-b pb-5 text-sm">
            <div class="flex justify-between">
              <dt class="text-muted-foreground">{{ t('cart.subtotal') }}</dt>
              <dd class="tabular-nums">{{ formatPrice(subtotal) }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-muted-foreground">{{ t('cart.tax') }}</dt>
              <dd class="tabular-nums">{{ formatPrice(tax) }}</dd>
            </div>
          </dl>

          <div class="flex items-baseline justify-between py-5">
            <span class="font-medium">{{ t('cart.total') }}</span>
            <span class="text-2xl font-semibold tabular-nums">{{ formatPrice(total) }}</span>
          </div>

          <div class="space-y-2">
            <Button size="lg" class="group h-12 w-full rounded-full" @click="dialogOpen = true">
              {{ t('cart.placeOrder') }}
              <ArrowRight class="transition-transform duration-300 group-hover:translate-x-1" />
            </Button>
            <Button variant="ghost" class="w-full rounded-full text-muted-foreground" as-child>
              <RouterLink :to="{ name: 'products' }">{{ t('cart.continue') }}</RouterLink>
            </Button>
          </div>
        </Card>
      </div>

      <!-- Empty cart -->
      <div v-else key="empty" class="flex flex-col items-center justify-center gap-5 rounded-3xl border border-dashed py-24 text-center">
        <div class="grid size-20 place-items-center rounded-full bg-muted">
          <ShoppingBag class="size-9 text-muted-foreground" />
        </div>
        <div class="space-y-1">
          <p class="font-display text-2xl font-semibold">{{ t('cart.empty.title') }}</p>
          <p class="text-muted-foreground">{{ t('cart.empty.hint') }}</p>
        </div>
        <Button size="lg" class="rounded-full" as-child>
          <RouterLink :to="{ name: 'products' }">{{ t('cart.empty.back') }} <ArrowRight /></RouterLink>
        </Button>
      </div>
    </Transition>

    <!-- Confirm order dialog -->
    <Dialog v-model:open="dialogOpen">
      <DialogContent class="rounded-3xl sm:max-w-md">
        <DialogHeader>
          <DialogTitle class="font-display text-2xl">{{ t('cart.dialog.title') }}</DialogTitle>
          <DialogDescription>{{ t('cart.dialog.desc') }}</DialogDescription>
        </DialogHeader>

        <div class="space-y-4 py-2">
          <div class="space-y-2">
            <Label for="order-name">{{ t('cart.dialog.name') }}</Label>
            <Input id="order-name" v-model="orderForm.name" class="h-11 rounded-xl" :placeholder="t('cart.dialog.namePh')" :disabled="placing" />
          </div>
          <div class="space-y-2">
            <Label for="order-phone">{{ t('cart.dialog.phone') }}</Label>
            <Input id="order-phone" v-model="orderForm.phone" type="tel" class="h-11 rounded-xl" placeholder="+373 60 000 000" :disabled="placing" />
          </div>

          <p v-if="placeError" class="text-sm text-destructive">{{ placeError }}</p>

          <div class="space-y-1.5 rounded-2xl bg-muted p-4 text-sm">
            <div class="flex justify-between text-muted-foreground">
              <span>{{ t('cart.dialog.items') }}</span><span class="tabular-nums">{{ cartStore.count }}</span>
            </div>
            <div class="flex justify-between font-semibold">
              <span>{{ t('cart.dialog.total') }}</span><span class="tabular-nums">{{ formatPrice(total) }}</span>
            </div>
          </div>
        </div>

        <DialogFooter class="gap-2">
          <Button variant="outline" class="rounded-full" :disabled="placing" @click="dialogOpen = false">{{ t('cart.dialog.cancel') }}</Button>
          <Button class="rounded-full" :disabled="placing" @click="confirmOrder">
            <Loader2 v-if="placing" class="animate-spin" />
            {{ placing ? t('cart.dialog.placing') : t('cart.dialog.confirm') }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { RouterLink } from 'vue-router';
import { useCartStore } from '@/store/cartStore';
import { useUserStore } from '@/store/userStore';
import CartService from '@/services/cartService';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Dialog, DialogContent, DialogHeader, DialogTitle,
  DialogDescription, DialogFooter,
} from '@/components/ui/dialog';
import CartItem from '@/components/parts/CartItem.vue';
import { ShoppingBag, CheckCircle, Loader2, ArrowRight } from 'lucide-vue-next';
import { useI18n } from '@/i18n';
import { formatPrice } from '@/lib/format.js';
import { toast } from 'vue-sonner';

const cartStore   = useCartStore();
const userStore   = useUserStore();
const { t }       = useI18n();

const dialogOpen    = ref(false);
const placing       = ref(false);
const placeError    = ref('');
const orderForm     = ref({ name: '', phone: '' });
const orderPlaced   = ref(false);
const placedOrderId = ref(null);

const subtotal = computed(() =>
    cartStore.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
);
const tax      = computed(() => subtotal.value * 0.1);
const total    = computed(() => subtotal.value + tax.value);

watch(dialogOpen, (open) => {
  if (open && !orderForm.value.name && userStore.user?.name) {
    orderForm.value.name = userStore.user.name;
  }
});

const orderSuccessMsg = computed(() => t('cart.placed.message', { id: placedOrderId.value }));

// Cart state is replaced only on success, so on failure the UI keeps the last valid values
const withErrorToast = (action, messageKey) => async (...args) => {
  try {
    await action(...args);
  } catch (e) {
    console.error(e);
    toast.error(t(messageKey));
  }
};

const removeFromCart = withErrorToast(cartStore.remove, 'cart.error.remove');
const updateQuantity = withErrorToast(cartStore.updateQuantity, 'cart.error.update');

const confirmOrder = async () => {
  placing.value    = true;
  placeError.value = '';

  try {
    const result = await CartService.placeOrder({
      name:  orderForm.value.name,
      phone: orderForm.value.phone,
    });

    if (result.success) {
      cartStore.items     = [];
      placedOrderId.value = result.order_id;
      dialogOpen.value    = false;
      orderPlaced.value   = true;
    } else {
      placeError.value = result.message || 'Something went wrong.';
    }
  } catch (e) {
    placeError.value = e.response?.data?.message || 'Failed to place order.';
  } finally {
    placing.value = false;
  }
};

onMounted(() => cartStore.fetchCart());
</script>