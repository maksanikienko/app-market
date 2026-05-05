<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Categories</h1>
    </div>

    <Card>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>ID</TableHead>
              <TableHead>Image</TableHead>
              <TableHead>Name</TableHead>
              <TableHead>Code</TableHead>
              <TableHead>Description</TableHead>
              <TableHead>Created At</TableHead>
              <TableHead>Updated At</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="category in categories" :key="category.id">
              <TableCell>{{ category.id }}</TableCell>

              <TableCell>
                <img
                    v-if="category.image"
                    :src="`/storage/${category.image}`"
                    class="w-14 h-14 object-cover rounded"
                />
              </TableCell>

              <TableCell>{{ category.name }}</TableCell>

              <TableCell>{{ category.code }}</TableCell>

              <TableCell class="max-w-xs truncate">
                {{ category.description }}
              </TableCell>

              <TableCell>{{ category.created_at ?? '-' }}</TableCell>

              <TableCell>{{ category.updated_at }}</TableCell>
            </TableRow>

            <TableRow v-if="!loading && !categories.length">
              <TableCell colspan="7" class="text-center py-6 text-muted-foreground">
                No categories found
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
import axios from 'axios'
import {Card, CardContent} from "./../../ui/card";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from "./../../ui/table";

const categories = ref([])
const loading = ref(false)

const getCategories = async () => {
  loading.value = true

  try {
    const { data } = await axios.get('/api/admin/categories')
    categories.value = data
  } catch (e) {
    console.error('Failed to load categories', e)
  } finally {
    loading.value = false
  }
}
onMounted(async () => {
  await getCategories()
})
</script>

