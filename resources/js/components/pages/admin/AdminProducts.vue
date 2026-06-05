<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between flex-wrap gap-2">
      <h1 class="text-2xl font-semibold">Товары</h1>
      <div class="flex items-center gap-2">
        <Button
          size="sm"
          :variant="trashed ? 'default' : 'outline'"
          @click="toggleTrash"
        >
          🗑 Корзина
        </Button>
        <Button v-if="!trashed" @click="router.push('/admin/products/create')">+ Новый товар</Button>
      </div>
    </div>

    <!-- Trash mode banner -->
    <div v-if="trashed" class="flex items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm text-amber-800">
      <span>Корзина — товары помечены на удаление. Восстановите или удалите навсегда.</span>
    </div>

    <!-- Filters -->
    <Card>
      <CardContent class="p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
          <input
            v-model="filters.code"
            placeholder="Код товара"
            class="h-9 rounded-md border border-input bg-background px-3 text-sm outline-none focus:ring-1 focus:ring-ring"
          />
          <input
            v-model="filters.name"
            placeholder="Название"
            class="h-9 rounded-md border border-input bg-background px-3 text-sm outline-none focus:ring-1 focus:ring-ring"
          />
          <select
            v-model="filters.category_id"
            class="h-9 rounded-md border border-input bg-background px-3 text-sm outline-none focus:ring-1 focus:ring-ring"
          >
            <option value="">Все категории</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
              {{ localeStore.t(cat.name) }}
            </option>
          </select>

          <div class="flex items-center gap-2">
            <button
              v-for="badge in badges"
              :key="badge.key"
              type="button"
              class="flex-1 h-9 rounded-md text-xs font-semibold border transition-colors"
              :class="filters[badge.key] === null
                ? 'border-border text-muted-foreground hover:border-foreground'
                : filters[badge.key]
                  ? 'border-transparent bg-emerald-100 text-emerald-700'
                  : 'border-transparent bg-rose-100 text-rose-600'"
              @click="cycleFlag(badge.key)"
            >
              {{ badge.label }}
              <span class="ml-0.5 opacity-60 text-[10px]">
                {{ filters[badge.key] === null ? '' : filters[badge.key] ? '✓' : '✗' }}
              </span>
            </button>
          </div>
        </div>

        <div class="flex justify-end mt-3">
          <button
            class="text-xs text-muted-foreground hover:text-foreground underline underline-offset-2"
            @click="resetFilters"
          >
            Сбросить фильтры
          </button>
        </div>
      </CardContent>
    </Card>

    <!-- Table -->
    <Card>
      <CardContent class="p-0 overflow-x-auto">
        <Table class="min-w-[640px]">
          <TableHeader>
            <TableRow>
              <TableHead class="w-12">ID</TableHead>
              <TableHead class="w-16">Фото</TableHead>
              <TableHead>Название</TableHead>
              <TableHead>Код</TableHead>
              <TableHead>Цена</TableHead>
              <TableHead>Категория</TableHead>
              <TableHead class="w-44">Действия</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow
              v-for="product in products"
              :key="product.id"
              :class="!product.is_active && !trashed ? 'opacity-40 bg-muted/40' : ''"
            >
              <TableCell class="text-muted-foreground">{{ product.id }}</TableCell>

              <TableCell>
                <img
                  v-if="product.media_items?.[0]"
                  :src="product.media_items[0].thumb_url"
                  class="w-12 h-12 object-cover rounded"
                />
              </TableCell>

              <TableCell class="font-medium">{{ localeStore.t(product.name) }}</TableCell>
              <TableCell>{{ product.code }}</TableCell>
              <TableCell>{{ product.price }}</TableCell>
              <TableCell>{{ localeStore.t(product.category?.name ?? '—') }}</TableCell>

              <TableCell>
                <!-- Normal mode actions -->
                <div v-if="!trashed" class="flex gap-1">
                  <Button size="sm" variant="outline" @click="router.push(`/admin/products/${product.id}/edit`)">
                    Изменить
                  </Button>
                  <Button
                    size="sm"
                    :disabled="deletingId === product.id"
                    class="bg-red-400 hover:bg-red-600/50"
                    @click="deleteTarget = product"
                  >
                    Удалить
                  </Button>
                </div>

                <!-- Trash mode actions -->
                <div v-else class="flex gap-1">
                  <Button
                    size="sm"
                    variant="outline"
                    :disabled="actionId === product.id"
                    @click="handleRestore(product)"
                  >
                    Восстановить
                  </Button>
                  <Button
                    size="sm"
                    :disabled="actionId === product.id"
                    class="bg-red-600 hover:bg-red-700 text-white"
                    @click="forceDeleteTarget = product"
                  >
                    Удалить навсегда
                  </Button>
                </div>
              </TableCell>
            </TableRow>

            <TableRow v-if="!loading && !products.length">
              <TableCell colspan="7" class="text-center py-8 text-muted-foreground">
                {{ trashed ? 'Корзина пуста' : 'Товары не найдены' }}
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <!-- Pagination -->
    <div v-if="meta.last_page > 1" class="flex items-center justify-between text-sm text-muted-foreground">
      <span>Всего: {{ meta.total }}</span>
      <div class="flex items-center gap-2">
        <Button size="sm" variant="outline" :disabled="meta.current_page <= 1" @click="goPage(meta.current_page - 1)">
          ←
        </Button>
        <span>{{ meta.current_page }} / {{ meta.last_page }}</span>
        <Button size="sm" variant="outline" :disabled="meta.current_page >= meta.last_page" @click="goPage(meta.current_page + 1)">
          →
        </Button>
      </div>
    </div>

    <!-- Soft-delete confirm -->
    <AlertDialog :open="!!deleteTarget" @update:open="val => { if (!val) deleteTarget = null }">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Удалить товар?</AlertDialogTitle>
          <AlertDialogDescription>
            «{{ localeStore.t(deleteTarget?.name ?? '') }}» будет перемещён в корзину. Восстановить можно через раздел «Корзина».
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <Button variant="outline" @click="deleteTarget = null">Отмена</Button>
          <Button class="bg-destructive text-white hover:bg-destructive/90" @click="confirmDelete">
            В корзину
          </Button>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>

    <!-- Force-delete confirm -->
    <AlertDialog :open="!!forceDeleteTarget" @update:open="val => { if (!val) forceDeleteTarget = null }">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Удалить навсегда?</AlertDialogTitle>
          <AlertDialogDescription>
            «{{ localeStore.t(forceDeleteTarget?.name ?? '') }}» будет удалён безвозвратно вместе со всеми изображениями. Это действие нельзя отменить.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <Button variant="outline" @click="forceDeleteTarget = null">Отмена</Button>
          <Button class="bg-destructive text-destructive-foreground hover:bg-destructive/90" @click="confirmForceDelete">
            Удалить навсегда
          </Button>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import {
  AlertDialog, AlertDialogContent, AlertDialogDescription,
  AlertDialogFooter, AlertDialogHeader, AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { useAdminProductService } from '@/services/adminProductService'
import { useLocaleStore } from '@/store/localeStore.js'
import { useCategoryStore } from '@/store/categoryStore.js'
import { useAdminProductFilters } from '@/composables/useAdminProductFilters.js'

const router      = useRouter()
const localeStore = useLocaleStore()

const categoryStore = useCategoryStore()
const { categories } = storeToRefs(categoryStore)

const { getAll, remove, restore, forceDelete } = useAdminProductService()
const { filters, reset, toParams } = useAdminProductFilters()

const products       = ref([])
const loading        = ref(false)
const trashed        = ref(false)
const deletingId     = ref(null)
const actionId       = ref(null)
const deleteTarget   = ref(null)
const forceDeleteTarget = ref(null)
const meta = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const badges = [
  { key: 'is_new',  label: 'Новинка' },
  { key: 'is_hit',  label: 'Хит'     },
  { key: 'is_sale', label: 'Скидка'  },
]

const cycleFlag = (key) => {
  if (filters[key] === null)  filters[key] = true
  else if (filters[key])      filters[key] = false
  else                        filters[key] = null
}

const load = async (page = 1) => {
  loading.value = true
  try {
    const params = { ...toParams(page), ...(trashed.value ? { trashed: 1 } : {}) }
    const res = await getAll(params)
    products.value = res.data
    meta.value     = res.meta
  } finally {
    loading.value = false
  }
}

const toggleTrash = () => {
  trashed.value = !trashed.value
  load(1)
}

const goPage = (page) => load(page)

const resetFilters = () => reset()

const confirmDelete = async () => {
  const product = deleteTarget.value
  deleteTarget.value = null
  deletingId.value = product.id
  try {
    await remove(product.id)
    products.value = products.value.filter(p => p.id !== product.id)
    meta.value.total--
  } finally {
    deletingId.value = null
  }
}

const handleRestore = async (product) => {
  actionId.value = product.id
  try {
    await restore(product.id)
    products.value = products.value.filter(p => p.id !== product.id)
    meta.value.total--
  } finally {
    actionId.value = null
  }
}

const confirmForceDelete = async () => {
  const product = forceDeleteTarget.value
  forceDeleteTarget.value = null
  actionId.value = product.id
  try {
    await forceDelete(product.id)
    products.value = products.value.filter(p => p.id !== product.id)
    meta.value.total--
  } finally {
    actionId.value = null
  }
}

let debounceTimer = null
watch(filters, () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => load(1), 300)
})

onMounted(async () => {
  if (!categories.value.length) await categoryStore.load()
  load()
})
</script>
