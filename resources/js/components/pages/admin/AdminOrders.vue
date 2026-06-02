<template>
  <div class="space-y-5">

    <!-- Header -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Заказы</h1>
      <span class="text-sm text-muted-foreground">{{ orders.length }} заказов</span>
    </div>

    <!-- Table -->
    <Card>
      <CardContent class="p-0">
        <div v-if="loading" class="py-12 text-center text-sm text-muted-foreground">
          Загрузка…
        </div>

        <Table v-else>
          <TableHeader>
            <TableRow>
              <TableHead class="w-14">№</TableHead>
              <TableHead class="w-44">Покупатель</TableHead>
              <TableHead>Товары</TableHead>
              <TableHead class="w-28 text-right">Сумма</TableHead>
              <TableHead class="w-36">Дата</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <template v-if="orders.length">
              <TableRow v-for="order in orders" :key="order.id" class="align-top">

                <!-- ID -->
                <TableCell class="pt-4">
                  <span class="text-xs font-mono font-semibold text-muted-foreground">#{{ order.id }}</span>
                </TableCell>

                <!-- Customer -->
                <TableCell class="pt-4">
                  <p class="font-medium text-sm leading-snug">{{ order.name || '—' }}</p>
                  <p class="text-xs text-muted-foreground mt-0.5">{{ order.phone || '—' }}</p>
                </TableCell>

                <!-- Products -->
                <TableCell class="py-3">
                  <div class="space-y-2">
                    <div
                      v-for="(product, idx) in visibleProducts(order)"
                      :key="product.id + '-' + idx"
                      class="flex items-start gap-2.5"
                    >
                      <!-- Thumbnail -->
                      <img
                        v-if="product.media_items?.[0]?.thumb_url"
                        :src="product.media_items[0].thumb_url"
                        class="w-10 h-10 rounded object-cover shrink-0 border"
                      />
                      <div
                        v-else
                        class="w-10 h-10 rounded bg-muted shrink-0 flex items-center justify-center"
                      >
                        <Package class="h-4 w-4 text-muted-foreground" />
                      </div>

                      <!-- Name + variant -->
                      <div class="min-w-0">
                        <p class="text-sm leading-snug truncate max-w-[280px]">
                          {{ localeStore.t(product.name) }}
                          <span class="text-muted-foreground ml-1">× {{ product.pivot?.count ?? 1 }}</span>
                        </p>
                        <span>{{product.slug}}</span>
                        <!-- Variant row -->
                        <div class="flex items-center gap-1.5 mt-0.5">
                          <span
                            v-if="product.pivot?.color_hex"
                            class="inline-block w-3 h-3 rounded-full border border-black/10 shrink-0"
                            :style="{ backgroundColor: product.pivot.color_hex }"
                          />
                          <span
                            v-if="product.pivot?.color"
                            class="text-[10px] text-muted-foreground"
                          >{{ colorLabel(product.pivot.color) }}</span>
                          <span
                            v-if="product.pivot?.size"
                            class="text-[10px] font-medium border rounded px-1.5 py-0.5 leading-none text-muted-foreground"
                          >{{ product.pivot.size }}</span>
                        </div>
                      </div>
                    </div>

                    <!-- Collapse toggle -->
                    <button
                      v-if="order.products.length > PREVIEW_LIMIT"
                      type="button"
                      class="text-[11px] text-primary hover:underline"
                      @click="toggleExpand(order.id)"
                    >
                      {{ expanded.has(order.id)
                          ? 'Свернуть'
                          : `Ещё ${order.products.length - PREVIEW_LIMIT} товар${pluralItems(order.products.length - PREVIEW_LIMIT)}` }}
                    </button>
                  </div>
                </TableCell>

                <!-- Amount -->
                <TableCell class="pt-4 text-right">
                  <span class="font-semibold text-sm">{{ orderAmount(order).toFixed(2) }} lei</span>
                </TableCell>

                <!-- Date -->
                <TableCell class="pt-4">
                  <p class="text-sm">{{ formatDate(order.created_at) }}</p>
                  <p class="text-[10px] text-muted-foreground mt-0.5">{{ formatTime(order.created_at) }}</p>
                </TableCell>

              </TableRow>
            </template>

            <TableRow v-else>
              <TableCell colspan="5" class="text-center py-12 text-muted-foreground">
                Заказов пока нет
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import axios from 'axios'
import { Package } from 'lucide-vue-next'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { useLocaleStore } from '@/store/localeStore.js'

const localeStore = useLocaleStore()
const orders  = ref([])
const loading = ref(false)
const expanded = ref(new Set())

const PREVIEW_LIMIT = 3

function colorLabel(nameJson) {
  if (!nameJson) return ''
  try {
    const obj = typeof nameJson === 'string' ? JSON.parse(nameJson) : nameJson
    return localeStore.t(obj)
  } catch {
    return nameJson
  }
}

function orderAmount(order) {
  return (order.products ?? []).reduce((sum, p) => sum + parseFloat(p.price) * (p.pivot?.count ?? 1), 0)
}

function visibleProducts(order) {
  return expanded.value.has(order.id)
    ? order.products
    : order.products.slice(0, PREVIEW_LIMIT)
}

function toggleExpand(id) {
  if (expanded.value.has(id)) {
    expanded.value.delete(id)
  } else {
    expanded.value.add(id)
  }
}

function pluralItems(n) {
  if (n % 10 === 1 && n % 100 !== 11) return ''
  if (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) return 'а'
  return 'ов'
}

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

function formatTime(iso) {
  return new Date(iso).toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}

onMounted(async () => {
  loading.value = true
  try {
    const { data } = await axios.get('/api/admin/orders')
    orders.value = data
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
})
</script>