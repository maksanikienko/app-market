<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Категории</h1>
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
              <TableCell class="max-w-xs truncate">{{ localeStore.t(category.description) }}</TableCell>
            </TableRow>

            <TableRow v-if="!loading && !categories.length">
              <TableCell colspan="5" class="text-center py-8 text-muted-foreground">
                Категории не найдены
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { useAdminCategoryService } from '@/services/adminCategoryService'
import { useLocaleStore } from '@/store/localeStore.js'

const { getAll }  = useAdminCategoryService()
const localeStore = useLocaleStore()

const categories = ref([])
const loading    = ref(false)

onMounted(async () => {
  loading.value = true
  try { categories.value = await getAll() }
  finally { loading.value = false }
})
</script>
