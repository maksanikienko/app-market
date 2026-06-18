<template>
  <div class="max-w-screen-xl mx-auto px-6 py-10 space-y-8">

    <!-- User info -->
    <div v-if="user">
      <h1 class="text-2xl font-semibold tracking-tight text-stone-900">{{ user.name }}</h1>
      <p class="text-sm text-stone-500 mt-1">{{ user.email }}</p>
    </div>

    <Separator />

    <!-- Section header -->
    <div class="flex items-center justify-between">
      <h2 class="text-xs font-semibold uppercase tracking-widest text-stone-400">{{ t('profile.orders.title') }}</h2>
      <span v-if="!loading" class="text-xs text-stone-400">{{ orders.length }} {{ t('profile.orders.count') }}</span>
    </div>

    <!-- Skeleton -->
    <div v-if="loading" class="space-y-4">
      <Card v-for="n in 2" :key="n">
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
    <div v-else-if="orders.length === 0" class="py-20 text-center">
      <ShoppingBag class="h-10 w-10 text-stone-200 mx-auto mb-3" />
      <p class="text-sm text-stone-400">{{ t('profile.orders.empty') }}</p>
    </div>

    <!-- Orders list -->
    <div v-else class="space-y-4">
      <Card v-for="order in orders" :key="order.id" class="overflow-hidden">

        <!-- Order header -->
        <CardHeader class="py-3.5 px-5 border-b border-stone-100 bg-stone-50">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <span class="text-xs font-mono font-semibold text-stone-500">
                {{ t('profile.order.id') }} #{{ order.id }}
              </span>
              <Separator orientation="vertical" class="h-3.5" />
              <span class="text-xs text-stone-400">{{ formatDate(order.created_at) }}</span>
              <template v-if="order.name">
                <Separator orientation="vertical" class="h-3.5" />
                <span class="text-xs text-stone-500">{{ order.name }}</span>
              </template>
              <template v-if="order.phone">
                <span class="text-xs text-stone-400">{{ order.phone }}</span>
              </template>
            </div>
            <span class="text-sm font-semibold text-stone-900">
              {{ t('profile.order.total') }}: {{ formatTotal(order) }} MDL
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
              <TableRow v-for="product in order.products" :key="product.id" class="hover:bg-stone-50">

                <!-- Thumbnail -->
                <TableCell class="pl-5 py-3">
                  <div class="w-12 h-12 rounded-lg overflow-hidden bg-stone-100 shrink-0">
                    <img
                      v-if="product.media_items?.[0]?.thumb_url"
                      :src="product.media_items[0].thumb_url"
                      :alt="localeStore.t(product.name)"
                      class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center">
                      <Package class="h-4 w-4 text-stone-300" />
                    </div>
                  </div>
                </TableCell>

                <!-- Name + variant -->
                <TableCell class="py-3">
                  <p class="text-sm text-stone-900 font-medium truncate max-w-xs">
                    {{ localeStore.t(product.name) }}
                  </p>
                  <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <TooltipProvider v-if="product.pivot?.color_hex">
                      <Tooltip>
                        <TooltipTrigger as-child>
                          <span
                            class="inline-block w-3.5 h-3.5 rounded-full border border-black/10 shrink-0 cursor-default"
                            :style="{ backgroundColor: product.pivot.color_hex }"
                          />
                        </TooltipTrigger>
                        <TooltipContent>{{ product.pivot.color }}</TooltipContent>
                      </Tooltip>
                    </TooltipProvider>
                    <span v-if="product.pivot?.color" class="text-[11px] text-stone-400">
                      {{ product.pivot.color }}
                    </span>
                    <Badge v-if="product.pivot?.size" variant="outline" class="text-[10px] px-1.5 py-0 h-5">
                      {{ product.pivot.size }}
                    </Badge>
                  </div>
                </TableCell>

                <!-- Qty -->
                <TableCell class="text-center py-3">
                  <span class="text-sm text-stone-600">× {{ product.pivot?.count ?? 1 }}</span>
                </TableCell>

                <!-- Line total -->
                <TableCell class="text-right pr-5 py-3">
                  <span class="text-sm font-semibold text-stone-900">{{ lineTotal(product) }} MDL</span>
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
import { useI18n } from '@/i18n.js'
import { Package, ShoppingBag } from 'lucide-vue-next'
import { Card, CardHeader, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import { Skeleton } from '@/components/ui/skeleton'
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/components/ui/table'
import { Tooltip, TooltipTrigger, TooltipContent, TooltipProvider } from '@/components/ui/tooltip'

const { t } = useI18n()
const userStore = useUserStore()
const localeStore = useLocaleStore()
const user = computed(() => userStore.user)

const { getOrders } = useProfileService()
const orders = ref([])
const loading = ref(true)

onMounted(async () => {
  if (!user.value) await userStore.fetchUser()
  try {
    orders.value = await getOrders()
  } finally {
    loading.value = false
  }
})

const lineTotal = (product) =>
  (parseFloat(product.price) * (product.pivot?.count ?? 1)).toFixed(2)

const formatTotal = (order) =>
  (order.products ?? [])
    .reduce((sum, p) => sum + parseFloat(p.price) * (p.pivot?.count ?? 1), 0)
    .toFixed(2)

const formatDate = (iso) =>
  new Date(iso).toLocaleDateString('ru-RU', { day: '2-digit', month: 'short', year: 'numeric' })
</script>
