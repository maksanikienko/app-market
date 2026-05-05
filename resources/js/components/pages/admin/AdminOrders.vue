<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Orders</h1>
    </div>

    <Card>
      <CardContent class="p-0">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>ID</TableHead>
              <TableHead>Name</TableHead>
              <TableHead>Phone</TableHead>
              <TableHead>Products</TableHead>
              <TableHead>Amount</TableHead>
              <TableHead>Status</TableHead>
              <TableHead>Created At</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="order in orders" :key="order.id">
              <TableCell>{{ order.id }}</TableCell>

              <TableCell>
                {{ order.name ?? '-' }}
              </TableCell>

              <TableCell>
                {{ order.phone ?? '-' }}
              </TableCell>

              <TableCell>
                <div class="space-y-1">
                  <div
                      v-for="product in order.products"
                      :key="product.id"
                      class="flex items-center gap-2"
                  >
                    <img
                        v-if="product.image"
                        :src="`/storage/${product.image}`"
                        class="w-10 h-10 rounded object-cover"
                    />
                    <span>
                      {{ product.name }} × {{ product.pivot?.count }}
                    </span>
                  </div>
                </div>
              </TableCell>

              <TableCell>
                {{ getOrderAmount(order) }}
              </TableCell>

              <TableCell>
                {{ order.status }}
              </TableCell>

              <TableCell>
                {{ order.created_at }}
              </TableCell>
            </TableRow>

            <TableRow v-if="!loading && !orders.length">
              <TableCell colspan="7" class="text-center py-6 text-muted-foreground">
                No orders found
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

const orders = ref([])
const loading = ref(false)

const getOrderAmount = (order) => {
  return order?.products.reduce((sum, p) => {
    return sum + p.price * (p.pivot?.count ?? 1)
  }, 0)
}
const getOrders = async () => {
  try {
    const { data } = await axios.get('/api/admin/orders')
    orders.value = data
    console.log(data)
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
}
onMounted(async () => {
  loading.value = true
  getOrders()
})
</script>

