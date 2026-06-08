<template>
  <div class="space-y-12 pb-16">

    <!-- Page header -->
    <section class="space-y-2">
      <h1 class="text-3xl font-semibold tracking-tight text-stone-900">{{ t('profile.title') }}</h1>
      <p class="text-stone-500 text-sm max-w-lg">{{ t('profile.subtitle') }}</p>
    </section>

    <template v-if="user">

      <!-- User info -->
      <Card>
        <CardContent class="flex items-center gap-4 p-5">
          <div class="h-14 w-14 rounded-full bg-stone-900 text-white flex items-center justify-center text-lg font-semibold shrink-0">
            {{ initials }}
          </div>
          <div class="min-w-0">
            <p class="text-base font-semibold text-stone-900 truncate">{{ user.name }}</p>
            <p class="text-sm text-stone-500 truncate">{{ user.email }}</p>
          </div>
        </CardContent>
      </Card>

      <!-- Order history -->
      <section class="space-y-4">
        <h2 class="text-xl font-semibold tracking-tight text-stone-900">{{ t('profile.orders.title') }}</h2>

        <!-- Loading -->
        <div v-if="loading" class="space-y-3">
          <Card v-for="i in 2" :key="i">
            <CardContent class="p-5 space-y-3">
              <Skeleton class="h-5 w-32" />
              <Skeleton class="h-16 w-full rounded-lg" />
            </CardContent>
          </Card>
        </div>

        <!-- Empty -->
        <Card v-else-if="!orders.length">
          <CardContent class="p-10 text-center space-y-4">
            <p class="text-sm text-stone-500">{{ t('profile.orders.empty') }}</p>
            <Button as-child>
              <RouterLink to="/products">{{ t('profile.orders.empty.cta') }}</RouterLink>
            </Button>
          </CardContent>
        </Card>

        <!-- List -->
        <div v-else class="space-y-3">
          <Card v-for="order in orders" :key="order.id">
            <CardHeader class="flex-row items-center justify-between gap-4 border-b border-stone-100">
              <div>
                <CardTitle class="text-sm">№ {{ order.id }}</CardTitle>
                <CardDescription class="text-xs">{{ formatDate(order.created_at) }}</CardDescription>
              </div>
              <div class="text-right space-y-1.5">
                <p class="text-sm font-semibold text-stone-900">{{ formatPrice(orderTotal(order)) }}</p>
                <Badge variant="secondary" class="font-normal">{{ orderItemsCount(order) }} {{ t('profile.orders.itemsCount') }}</Badge>
              </div>
            </CardHeader>

            <CardContent class="p-0">
              <div class="divide-y divide-stone-100">
                <div v-for="(product, idx) in visibleProducts(order)" :key="product.id + '-' + idx" class="flex items-center gap-3 px-6 py-3">
                  <img
                    v-if="product.media_items?.[0]?.thumb_url"
                    :src="product.media_items[0].thumb_url"
                    class="w-12 h-12 rounded-lg object-cover border border-stone-100 shrink-0"
                  />
                  <div v-else class="w-12 h-12 rounded-lg bg-stone-100 shrink-0 flex items-center justify-center">
                    <Package class="h-4 w-4 text-stone-400" />
                  </div>

                  <div class="min-w-0 flex-1">
                    <p class="text-sm text-stone-900 truncate">{{ localeStore.t(product.name) }}</p>
                    <div class="flex items-center gap-1.5 mt-1">
                      <span
                        v-if="product.pivot?.color_hex"
                        class="inline-block w-3 h-3 rounded-full border border-stone-200 shrink-0"
                        :style="{ backgroundColor: product.pivot.color_hex }"
                      />
                      <Badge v-if="product.pivot?.size" variant="outline" class="text-[10px] font-normal text-stone-400">
                        {{ product.pivot.size }}
                      </Badge>
                      <span class="text-xs text-stone-400">× {{ product.pivot?.count ?? 1 }}</span>
                    </div>
                  </div>

                  <p class="text-sm font-medium text-stone-900 shrink-0">{{ formatPrice(product.price * (product.pivot?.count ?? 1)) }}</p>
                </div>
              </div>
            </CardContent>

            <template v-if="order.products.length > PREVIEW_LIMIT">
              <Separator />
              <Button
                variant="ghost"
                class="w-full rounded-none rounded-b-xl text-xs text-stone-500 hover:text-stone-900 h-auto py-2.5"
                @click="toggleExpand(order.id)"
              >
                {{ expanded.has(order.id) ? t('profile.orders.toggle.less') : `${t('profile.orders.toggle.more')} (${order.products.length - PREVIEW_LIMIT})` }}
              </Button>
            </template>
          </Card>
        </div>
      </section>
    </template>

    <p v-else class="text-sm text-stone-400">{{ t('profile.orders.empty') }}</p>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useUserStore } from '@/store/userStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useProfileService } from '@/services/profileService.js';
import { useI18n } from '@/i18n';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Skeleton } from '@/components/ui/skeleton';
import { Package } from 'lucide-vue-next';

const PREVIEW_LIMIT = 3;

const userStore = useUserStore();
const localeStore = useLocaleStore();
const { t } = useI18n();
const { getOrders } = useProfileService();

const user = computed(() => userStore.user);

const loading = ref(false);
const orders = ref([]);
const expanded = ref(new Set());

onMounted(async () => {
  if (!user.value) {
    await userStore.fetchUser();
  }
  await loadOrders();
});

async function loadOrders() {
  loading.value = true;
  try {
    orders.value = await getOrders();
  } finally {
    loading.value = false;
  }
}

const initials = computed(() => {
  const parts = (user.value?.name ?? '').split(' ').filter(Boolean);
  return parts.slice(0, 2).map((part) => part[0]?.toUpperCase()).join('') || '—';
});

function formatPrice(value) {
  return `${Number(value ?? 0).toFixed(2)} lei`;
}

function formatDate(value) {
  if (!value) return '—';
  const locale = localeStore.current === 'ro' ? 'ro-RO' : 'ru-RU';
  return new Date(value).toLocaleDateString(locale, { day: 'numeric', month: 'short', year: 'numeric' });
}

function orderTotal(order) {
  return (order.products ?? []).reduce((sum, p) => sum + parseFloat(p.price) * (p.pivot?.count ?? 1), 0);
}

function orderItemsCount(order) {
  return (order.products ?? []).reduce((sum, p) => sum + (p.pivot?.count ?? 1), 0);
}

function visibleProducts(order) {
  if (expanded.value.has(order.id)) return order.products;
  return order.products.slice(0, PREVIEW_LIMIT);
}

function toggleExpand(id) {
  if (expanded.value.has(id)) expanded.value.delete(id);
  else expanded.value.add(id);
  expanded.value = new Set(expanded.value);
}
</script>
