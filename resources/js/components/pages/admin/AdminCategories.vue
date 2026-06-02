<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Категории</h1>
<!--      <Button @click="router.push('/admin/categories/create')">+ Новая категория</Button>-->
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
              <TableHead>Описание</TableHead>
<!--              <TableHead class="w-36">Действия</TableHead>-->
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="category in categories" :key="category.id">
              <TableCell class="text-muted-foreground">{{ category.id }}</TableCell>

              <TableCell>
                <img
                  :src="`/storage/categories/${category.slug}.png`"
                  :alt="localeStore.t(category.name)"
                  class="w-12 h-12 object-cover rounded"
                />
              </TableCell>

              <TableCell class="font-medium">{{ localeStore.t(category.name) }}</TableCell>
              <TableCell>{{ category.slug }}</TableCell>
              <TableCell class="max-w-xs truncate">{{ localeStore.t(category.description)  }}</TableCell>

<!--              <TableCell>-->
<!--                <div class="flex gap-1">-->
<!--                  <Button size="sm" variant="outline" @click="router.push(`/admin/categories/${category.id}/edit`)">-->
<!--                    Изменить-->
<!--                  </Button>-->
<!--                  <Button-->
<!--                    size="sm"-->
<!--                    :disabled="deletingId === category.id"-->
<!--                    class="bg-red-400 hover:bg-red-600/50"-->
<!--                    @click="deleteTarget = category"-->
<!--                  >-->
<!--                    Удалить-->
<!--                  </Button>-->
<!--                </div>-->
<!--              </TableCell>-->
            </TableRow>

            <TableRow v-if="!loading && !categories.length">
              <TableCell colspan="6" class="text-center py-8 text-muted-foreground">
                Категории не найдены
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <AlertDialog :open="!!deleteTarget" @update:open="val => { if (!val) deleteTarget = null }">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Удалить категорию?</AlertDialogTitle>
          <AlertDialogDescription>
            «{{ deleteTarget?.name }}» будет удалена безвозвратно. Это действие нельзя отменить.
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
import { useAdminCategoryService } from '@/services/adminCategoryService'
import {useLocaleStore} from "@/store/localeStore.js";

const router = useRouter()
const { getAll, remove } = useAdminCategoryService()

const localeStore = useLocaleStore()

const categories = ref([])
const loading = ref(false)
const deletingId = ref(null)
const deleteTarget = ref(null)

const load = async () => {
  loading.value = true
  try { categories.value = await getAll() }
  finally { loading.value = false }
}

const confirmDelete = async () => {
  const category = deleteTarget.value
  deleteTarget.value = null
  deletingId.value = category.id
  try {
    await remove(category.id)
    categories.value = categories.value.filter(c => c.id !== category.id)
  } finally {
    deletingId.value = null
  }
}

onMounted(load)
</script>
