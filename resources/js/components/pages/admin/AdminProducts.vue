<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Products</h1>
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
              <TableHead>Price</TableHead>
              <TableHead>Category</TableHead>
              <TableHead>Description</TableHead>
              <TableHead>Created</TableHead>
              <TableHead>Updated</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="product in products" :key="product.id">
              <TableCell>{{ product.id }}</TableCell>

              <TableCell>
                <img
                  v-if="product.image"
                  :src="`/storage/${product.image}`"
                  class="w-14 h-14 object-cover rounded"
                />
              </TableCell>

              <TableCell>{{ product.name }}</TableCell>

              <TableCell>{{ product.code }}</TableCell>

              <TableCell>{{ product.price }}</TableCell>

              <TableCell>
                {{ product.category?.name ?? '-' }}
              </TableCell>

              <TableCell class="max-w-xs truncate">
                {{ product.description }}
              </TableCell>

              <TableCell>{{ product.created_at ?? '-' }}</TableCell>

              <TableCell>{{ product.updated_at }}</TableCell>
            </TableRow>

            <TableRow v-if="!loading && !products.length">
              <TableCell colspan="9" class="text-center py-6 text-muted-foreground">
                Products not found
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

const products = ref([])
const loading = ref(false)

const getProducts = async () => {
  try {
    const { data } = await axios.get('/api/admin/products')
    products.value = data
  } catch (e) {
    console.error('Failed to load products', e)
  } finally {
    loading.value = false
  }
}
onMounted(async () => {
  loading.value = true
  await getProducts()
})
</script>

