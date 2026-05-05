<template>
  <div class="min-h-screen flex flex-col">
    <Header />

    <main class="flex-1 container mx-auto px-4 py-6">
      <div class="mb-4 flex items-center gap-2 text-sm text-muted-foreground">
        <RouterLink to="/" class="hover:underline">Shop</RouterLink>
        <span>/</span>
        <span class="font-medium text-foreground">Admin Panel</span>
      </div>

      <div class="mb-6 flex flex-wrap gap-2">
        <Button size="sm" variant="outline" @click="$router.push('/admin/products')">
          Products
        </Button>
        <Button size="sm" variant="outline" @click="$router.push('/admin/categories')">
          Categories
        </Button>
        <Button size="sm" variant="outline" @click="$router.push('/admin/orders')">
          Orders
        </Button>
      </div>

      <router-view />
    </main>
  </div>
</template>

<script setup>
import Header from '@/components/layouts/Header.vue'
import { Button } from '@/components/ui/button'
</script>

