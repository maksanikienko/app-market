<template>
  <div class="space-y-8">

    <!-- User info -->
    <div v-if="user" class="animate-rise flex items-center gap-4">
      <span class="grid size-16 place-items-center rounded-2xl bg-brand/15 font-display text-2xl font-semibold text-brand">
        {{ user.name?.[0]?.toUpperCase() }}
      </span>
      <div>
        <h1 class="font-display text-3xl md:text-4xl font-semibold leading-none tracking-tight">{{ user.name }}</h1>
        <p class="mt-1.5 text-sm text-muted-foreground">{{ user.email }}</p>
      </div>
    </div>

    <Separator />

    <!-- Section header -->
    <div class="flex items-center justify-between">
      <h2 class="text-xs font-medium uppercase tracking-[0.2em] text-brand">{{ t('profile.orders.title') }}</h2>
      <span v-if="!loading" class="text-xs text-muted-foreground">{{ orders.length }} {{ t('profile.orders.count') }}</span>
    </div>

    <!-- Skeleton -->
    <div v-if="loading" class="space-y-4">
      <Card v-for="n in 2" :key="n" class="rounded-2xl shadow-none">
        <CardHeader class="pb-3">
          <div class="flex justify-between">
            <Skeleton class="h-4 w-24" />
            <Skeleton class="h-4 w-16" />
          </div>
        </CardHeader>
        <CardContent class="space-y-3">
          <div v-for="i in 2" :key="i" class="flex items-center gap-4">
            <Skeleton class="w-14 h-14 rounded-lg shrink-0" />
            <div class="flex-1 space-y-2">
              <Skeleton class="h-3.5 w-48" />
              <Skeleton class="h-3 w-24" />
            </div>
            <Skeleton class="h-4 w-16" />
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Empty -->
    <div v-else-if="orders.length === 0" class="rounded-3xl border border-dashed py-20 text-center">
      <ShoppingBag class="h-10 w-10 text-muted-foreground/40 mx-auto mb-3" />
      <p class="text-sm text-muted-foreground">{{ t('profile.orders.empty') }}</p>
    </div>

    <!-- Orders list -->
    <div v-else class="space-y-4">
      <Card v-for="(order, i) in orders" :key="order.id" class="animate-rise stagger overflow-hidden rounded-2xl shadow-none" :style="{ '--i': i }">

        <!-- Order header -->
        <CardHeader class="py-3.5 px-5 border-b bg-muted/50">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <span class="text-xs font-mono font-semibold text-muted-foreground">
                {{ t('profile.order.id') }} #{{ order.id }}
              </span>
              <Separator orientation="vertical" class="h-3.5" />
              <span class="text-xs text-muted-foreground">{{ formatDate(order.created_at) }}</span>
              <template v-if="order.name">
                <Separator orientation="vertical" class="h-3.5" />
                <span class="text-xs text-muted-foreground">{{ order.name }}</span>
              </template>
              <template v-if="order.phone">
                <span class="text-xs text-muted-foreground">{{ order.phone }}</span>
              </template>
            </div>
            <span class="text-sm font-semibold text-foreground">
              {{ t('profile.order.total') }}: {{ formatPrice(orderTotal(order), 'MDL') }}
            </span>
          </div>
        </CardHeader>

        <!-- Products table -->
        <CardContent class="p-0">
          <Table>
            <TableHeader>
              <TableRow class="hover:bg-transparent">
                <TableHead class="pl-5 w-16"></TableHead>
                <TableHead>{{ t('profile.order.name') }}</TableHead>
                <TableHead class="w-32 text-center">{{ t('profile.order.qty') }}</TableHead>
                <TableHead class="w-36 text-right pr-5">{{ t('profile.order.price') }}</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="product in order.products" :key="product.id" class="hover:bg-muted/40">

                <!-- Thumbnail -->
                <TableCell class="pl-5 py-3">
                  <div class="w-12 h-12 rounded-lg overflow-hidden bg-muted shrink-0">
                    <img
                      v-if="product.media_items?.[0]?.thumb_url"
                      :src="product.media_items[0].thumb_url"
                      :alt="localeStore.t(product.name)"
                      class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center">
                      <Package class="h-4 w-4 text-muted-foreground/50" />
                    </div>
                  </div>
                </TableCell>

                <!-- Name + variant -->
                <TableCell class="py-3">
                  <p class="text-sm text-foreground font-medium truncate max-w-xs">
                    {{ localeStore.t(product.name) }}
                  </p>
                  <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <span
                      v-if="product.pivot?.color_hex"
                      class="inline-block w-3.5 h-3.5 rounded-full border border-black/10 shrink-0"
                      :style="{ backgroundColor: product.pivot.color_hex }"
                    />
                    <span v-if="product.pivot?.color" class="text-[11px] text-muted-foreground">
                      {{ localeStore.t(product.pivot.color) }}
                    </span>
                    <Badge v-if="product.pivot?.size" variant="outline" class="text-[10px] px-1.5 py-0 h-5">
                      {{ product.pivot.size }}
                    </Badge>
                  </div>
                </TableCell>

                <!-- Qty -->
                <TableCell class="text-center py-3">
                  <span class="text-sm text-muted-foreground">× {{ product.pivot?.count ?? 1 }}</span>
                </TableCell>

                <!-- Line total -->
                <TableCell class="text-right pr-5 py-3">
                  <span class="text-sm font-semibold text-foreground">{{ formatPrice(lineTotal(product), 'MDL') }}</span>
                </TableCell>

              </TableRow>
            </TableBody>
          </Table>
        </CardContent>

      </Card>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useUserStore } from '@/store/userStore.js'
import { useLocaleStore } from '@/store/localeStore.js'
import { useProfileService } from '@/services/profileService.js'
import { useI18n } from '@/i18n'
import { formatPrice, lineTotal, orderTotal } from '@/lib/format.js'
import { Package, ShoppingBag } from 'lucide-vue-next'
import { Card, CardHeader, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import { Skeleton } from '@/components/ui/skeleton'
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/components/ui/table'

const { t } = useI18n()
const userStore = useUserStore()
const localeStore = useLocaleStore()
const user = computed(() => userStore.user)

const { getOrders } = useProfileService()
const orders = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    orders.value = await getOrders()
  } finally {
    loading.value = false
  }
})

const formatDate = (iso) =>
  new Date(iso).toLocaleDateString('ru-RU', { day: '2-digit', month: 'short', year: 'numeric' })
</script>
