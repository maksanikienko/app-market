<template>
  <div class="space-y-8">
    <h1 class="text-3xl font-bold">Shopping Cart</h1>

    <!-- ── Success screen ──────────────────────────────────────────── -->
    <div v-if="orderPlaced" class="flex flex-col items-center justify-center gap-6 py-20 rounded-2xl border bg-muted/30">
      <div class="h-20 w-20 rounded-full bg-emerald-100 flex items-center justify-center">
        <CheckCircle class="h-10 w-10 text-emerald-600" />
      </div>
      <div class="text-center space-y-1">
        <h2 class="text-2xl font-bold">Order Placed!</h2>
        <p class="text-muted-foreground">
          Order #{{ placedOrderId }} was successfully placed. We'll contact you shortly.
        </p>
      </div>
      <RouterLink to="/products">
        <Button size="lg">Continue Shopping</Button>
      </RouterLink>
    </div>

    <!-- ── Cart content ────────────────────────────────────────────── -->
    <div v-else-if="cartStore.items.length" class="grid gap-8 lg:grid-cols-3">

      <!-- Items -->
      <div class="lg:col-span-2 space-y-4">
        <CartItem
            v-for="item in cartStore.items"
            :key="item.id"
            :item="item"
            @remove="removeFromCart"
            @update-quantity="updateQuantity"
        />
      </div>

      <!-- Summary -->
      <div class="h-fit rounded-lg border p-6 space-y-4 sticky top-20">
        <h2 class="font-semibold text-lg">Summary</h2>

        <div class="space-y-2 text-sm border-b pb-4">
          <div class="flex justify-between">
            <span class="text-muted-foreground">Subtotal:</span>
            <span>${{ subtotal.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-muted-foreground">Shipping:</span>
            <span>{{ shipping === 0 ? 'Free' : `$${shipping.toFixed(2)}` }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-muted-foreground">Tax (10%):</span>
            <span>${{ tax.toFixed(2) }}</span>
          </div>
        </div>

        <div class="flex justify-between text-lg font-bold">
          <span>Total:</span>
          <span>${{ total.toFixed(2) }}</span>
        </div>

        <!-- Place Order — opens dialog -->
        <Button class="w-full" @click="dialogOpen = true">
          Place Order
        </Button>
        <Button variant="outline" class="w-full" as-child>
          <RouterLink to="/products">Continue Shopping</RouterLink>
        </Button>

        <Separator />

        <div class="space-y-2">
          <Label for="promo">Promo Code</Label>
          <div class="flex gap-2">
            <Input id="promo" placeholder="Code" v-model="promoCode" />
            <Button variant="outline" @click="applyPromo">Apply</Button>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Empty cart ──────────────────────────────────────────────── -->
    <div v-else class="flex h-96 items-center justify-center rounded-lg border border-dashed">
      <div class="text-center space-y-4">
        <ShoppingCart class="h-16 w-16 mx-auto text-muted-foreground" />
        <div>
          <p class="text-lg font-semibold">Your cart is empty</p>
          <p class="text-muted-foreground">Add some products</p>
        </div>
        <Button as-child>
          <RouterLink to="/products">Back to Products</RouterLink>
        </Button>
      </div>
    </div>

    <!-- ── Confirm order dialog ────────────────────────────────────── -->
    <Dialog v-model:open="dialogOpen">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>Confirm Order</DialogTitle>
          <DialogDescription>
            Leave your contact details so we can reach you (optional).
          </DialogDescription>
        </DialogHeader>

        <div class="space-y-4 py-2">
          <div class="space-y-1">
            <Label for="order-name">Name</Label>
            <Input id="order-name" v-model="orderForm.name" placeholder="Your name" :disabled="placing" />
          </div>
          <div class="space-y-1">
            <Label for="order-phone">Phone</Label>
            <Input id="order-phone" v-model="orderForm.phone" placeholder="+1 234 567 8900" :disabled="placing" />
          </div>

          <p v-if="placeError" class="text-sm text-destructive">{{ placeError }}</p>

          <!-- Order summary inside dialog -->
          <div class="rounded-lg bg-muted/40 p-4 space-y-1 text-sm">
            <div class="flex justify-between text-muted-foreground">
              <span>Items:</span><span>{{ cartStore.count }}</span>
            </div>
            <div class="flex justify-between font-semibold">
              <span>Total:</span><span>${{ total.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <DialogFooter>
          <Button variant="outline" @click="dialogOpen = false" :disabled="placing">Cancel</Button>
          <Button @click="confirmOrder" :disabled="placing">
            <Loader2 v-if="placing" class="h-4 w-4 mr-2 animate-spin" />
            {{ placing ? 'Placing...' : 'Confirm Order' }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useCartStore } from '@/store/cartStore';
import CartService from '@/services/cartService';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import {
  Dialog, DialogContent, DialogHeader, DialogTitle,
  DialogDescription, DialogFooter,
} from '@/components/ui/dialog';
import CartItem from '@/components/parts/CartItem.vue';
import { ShoppingCart, CheckCircle, Loader2 } from 'lucide-vue-next';

const cartStore = useCartStore();
const promoCode = ref('');

// Dialog state
const dialogOpen   = ref(false);
const placing      = ref(false);
const placeError   = ref('');
const orderForm    = ref({ name: '', phone: '' });

// Success state
const orderPlaced  = ref(false);
const placedOrderId = ref(null);

// ── Totals ────────────────────────────────────────────────────────────────────
const subtotal = computed(() =>
    cartStore.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
);
const shipping = computed(() => subtotal.value > 50 ? 0 : 10);
const tax      = computed(() => subtotal.value * 0.1);
const total    = computed(() => subtotal.value + shipping.value + tax.value);

// ── Cart actions ──────────────────────────────────────────────────────────────
const removeFromCart = async (productId) => {
  try {
    await cartStore.remove(productId);
  } catch (e) {
    console.error('Failed to remove product', e);
  }
};

const updateQuantity = async (productId, quantity) => {
  try {
    await cartStore.updateQuantity(productId, quantity);
  } catch (e) {
    console.error('Failed to update quantity', e);
  }
};

// ── Place order ───────────────────────────────────────────────────────────────
const confirmOrder = async () => {
  placing.value   = true;
  placeError.value = '';

  try {
    const result = await CartService.placeOrder({
      name:  orderForm.value.name,
      phone: orderForm.value.phone,
    });

    if (result.success) {
      // Clear local cart state
      cartStore.items = [];
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

const applyPromo = () => {};

onMounted(() => cartStore.fetchCart());
</script>
