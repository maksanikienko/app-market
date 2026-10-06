<template>
  <div class="min-h-screen flex flex-col">
    <Header />

    <main class="flex-1 container mx-auto px-4 py-6">
      <div class="mb-4 flex items-center gap-2 text-sm text-muted-foreground">
        <RouterLink to="/" class="hover:underline">Магазин</RouterLink>
        <span>/</span>
        <span class="font-medium text-foreground">Панель Администратора</span>
      </div>

      <nav class="mb-6 flex flex-wrap gap-2">
        <Button
          v-for="link in NAV_LINKS"
          :key="link.to"
          as-child
          size="sm"
          :variant="$route.path.startsWith(link.to) ? 'default' : 'outline'"
        >
          <RouterLink :to="link.to">{{ link.label }}</RouterLink>
        </Button>
      </nav>

      <router-view />
    </main>
  </div>
</template>

<script setup>
import Header from '@/components/layouts/Header.vue'
import { Button } from '@/components/ui/button'

const NAV_LINKS = [
  { to: '/admin/products',   label: 'Продукты' },
  { to: '/admin/categories', label: 'Категории' },
  { to: '/admin/orders',     label: 'Заказы' },
  { to: '/admin/stock',      label: 'Склад' },
  { to: '/admin/errors',     label: 'Ошибки' },
]
</script>
