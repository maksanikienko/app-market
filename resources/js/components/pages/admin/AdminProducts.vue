<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Товары</h1>
      <Button @click="router.push('/admin/products/create')">+ Новый товар</Button>
    </div>

    <Card>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead class="w-12">ID</TableHead>
              <TableHead class="w-16">Фото</TableHead>
              <TableHead>Название</TableHead>
              <TableHead>Код</TableHead>
              <TableHead>Цена</TableHead>
              <TableHead>Категория</TableHead>
              <TableHead class="w-36">Действия</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="product in products" :key="product.id">
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
                <div class="flex gap-1">
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
              </TableCell>
            </TableRow>

            <TableRow v-if="!loading && !products.length">
              <TableCell colspan="7" class="text-center py-8 text-muted-foreground">
                Товары не найдены
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <AlertDialog :open="!!deleteTarget" @update:open="val => { if (!val) deleteTarget = null }">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Удалить товар?</AlertDialogTitle>
          <AlertDialogDescription>
            «{{ deleteTarget?.name }}» будет удалён безвозвратно. Это действие нельзя отменить.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <Button variant="outline" @click="deleteTarget = null">Отмена</Button>
          <Button class="bg-destructive text-destructive-foreground hover:bg-destructive/90" @click="confirmDelete">
            Удалить
          </Button>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import {
  AlertDialog, AlertDialogContent, AlertDialogDescription,
  AlertDialogFooter, AlertDialogHeader, AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { useAdminProductService } from '@/services/adminProductService'
import {useLocaleStore} from "@/store/localeStore.js";

const localeStore = useLocaleStore()

const router = useRouter()
const { getAll, remove } = useAdminProductService()

const products = ref([])
const loading = ref(false)
const deletingId = ref(null)
const deleteTarget = ref(null)

const load = async () => {
  loading.value = true
  try { products.value = await getAll() }
  finally { loading.value = false }
}

const confirmDelete = async () => {
  const product = deleteTarget.value
  deleteTarget.value = null
  deletingId.value = product.id
  try {
    await remove(product.id)
    products.value = products.value.filter(p => p.id !== product.id)
  } finally {
    deletingId.value = null
  }
}

onMounted(load)
</script>
