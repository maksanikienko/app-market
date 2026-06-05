<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-semibold">Остатки по локациям</h1>

    <div v-if="loading" class="text-muted-foreground">Загрузка…</div>

    <template v-else>
      <div v-for="loc in stock" :key="loc.id ?? 'unassigned'" class="space-y-1">
        <div class="flex items-center gap-3 mb-2">
          <h2 class="text-lg font-medium">{{ loc.name }}</h2>
          <span v-if="loc.type" class="text-xs px-2 py-0.5 rounded-full border font-medium"
            :class="loc.type === 'store' ? 'border-blue-300 text-blue-700 bg-blue-50' : 'border-amber-300 text-amber-700 bg-amber-50'">
            {{ loc.type === 'store' ? 'Магазин' : 'Склад' }}
          </span>
          <span class="text-sm text-muted-foreground">всего: <strong>{{ loc.total_stock }}</strong> шт.</span>
        </div>

        <Card>
          <CardContent class="p-0">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Товар</TableHead>
                  <TableHead class="w-28">Код</TableHead>
                  <TableHead class="w-28">Цвет</TableHead>
                  <TableHead class="w-20">Размер</TableHead>
                  <TableHead class="w-36">SKU</TableHead>
                  <TableHead class="w-24 text-right">Остаток</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-if="!loc.variants.length">
                  <TableCell colspan="5" class="text-center py-6 text-muted-foreground">Нет товаров</TableCell>
                </TableRow>
                <TableRow v-for="v in loc.variants" :key="v.id">
                  <TableCell class="font-medium">{{ localeStore.t(v.product_name) }}</TableCell>
                  <TableCell class="text-muted-foreground text-xs font-mono">{{ v.product_code || '—' }}</TableCell>
                  <TableCell>
                    <div class="flex items-center gap-2">
                      <span v-if="v.color_hex" class="inline-block w-3.5 h-3.5 rounded-sm border border-black/10 shrink-0"
                        :style="`background:${v.color_hex}`" />
                      <span class="text-sm">{{ colorLabel(v.color) }}</span>
                    </div>
                  </TableCell>
                  <TableCell>{{ v.size }}</TableCell>
                  <TableCell class="text-muted-foreground text-xs font-mono">{{ v.sku || '—' }}</TableCell>
                  <TableCell class="text-right font-semibold" :class="v.stock === 0 ? 'text-destructive' : ''">
                    {{ v.stock }}
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </CardContent>
        </Card>
      </div>

      <p v-if="!stock.length" class="text-muted-foreground">Нет данных.</p>
    </template>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { useAdminLocationService } from '@/services/adminLocationService'
import { useLocaleStore } from '@/store/localeStore'

const { getStock } = useAdminLocationService()
const localeStore  = useLocaleStore()

const stock   = ref([])
const loading = ref(false)

function colorLabel(colorJson) {
  try {
    const obj = typeof colorJson === 'string' ? JSON.parse(colorJson) : colorJson
    return localeStore.t(obj)
  } catch {
    return colorJson ?? '—'
  }
}

onMounted(async () => {
  loading.value = true
  try { stock.value = await getStock() }
  finally { loading.value = false }
})
</script>
