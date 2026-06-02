<template>
  <div class="min-h-screen flex flex-col">
    <Header />

    <main class="flex-1 container mx-auto px-4 py-6">
      <div class="mb-4 flex items-center gap-2 text-sm text-muted-foreground">
        <RouterLink to="/" class="hover:underline">Магазин</RouterLink>
        <span>/</span>
        <span class="font-medium text-foreground">Панель Администратора</span>
      </div>

      <div class="mb-6 flex flex-wrap gap-2">
        <Button size="sm" variant="outline" @click="$router.push('/admin/products')">
          Продукты
        </Button>
        <Button size="sm" variant="outline" @click="$router.push('/admin/categories')">
          Категории
        </Button>
        <Button size="sm" variant="outline" @click="$router.push('/admin/orders')">
          Заказы
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

