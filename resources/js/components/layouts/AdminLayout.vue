<template>
  <div class="flex min-h-svh flex-col bg-background">
    <header class="glass sticky top-0 z-30 border-b select-none">
      <div class="flex h-16 items-center gap-3 px-4 sm:px-6">
        <RouterLink :to="{ name: 'admin-orders' }" class="flex items-center gap-2.5">
          <span class="grid size-8 place-items-center rounded-lg bg-primary font-display text-lg italic text-primary-foreground">F</span>
          <span class="text-sm font-semibold tracking-[0.3em]">FORYOU</span>
          <Badge variant="secondary" class="rounded-full">Admin</Badge>
        </RouterLink>

        <div class="ml-auto flex items-center gap-1">
          <Button variant="ghost" size="sm" class="rounded-full text-muted-foreground" as-child>
            <RouterLink :to="{ name: 'home' }"><Store /> <span class="hidden sm:inline">Магазин</span></RouterLink>
          </Button>
          <ThemeToggle />
          <UserMenu />
        </div>
      </div>

      <nav class="flex gap-1 overflow-x-auto px-4 sm:px-6">
        <RouterLink
          v-for="link in NAV_LINKS"
          :key="link.label"
          :to="link.to"
          class="relative flex shrink-0 items-center gap-2 px-3 py-3 text-sm transition-colors"
          :class="isActive(link) ? 'font-medium text-foreground' : 'text-muted-foreground hover:text-foreground'"
        >
          <component :is="link.icon" class="size-4" />
          {{ link.label }}
          <span
            class="absolute inset-x-2 -bottom-px h-0.5 rounded-full bg-brand transition-transform duration-300 ease-out-expo"
            :class="isActive(link) ? 'scale-x-100' : 'scale-x-0'"
          />
        </RouterLink>
      </nav>
    </header>

    <main class="w-full flex-1 px-4 py-6 sm:px-6">
      <RouterView v-slot="{ Component, route: r }">
        <Transition name="page" mode="out-in">
          <component :is="Component" :key="r.path" />
        </Transition>
      </RouterView>
    </main>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { Boxes, ClipboardList, FolderTree, Package, Store, TriangleAlert } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import ThemeToggle from '@/components/parts/ThemeToggle.vue'
import UserMenu from '@/components/parts/UserMenu.vue'

const NAV_LINKS = [
  { to: { name: 'admin-products' },   label: 'Продукты',  icon: Package },
  { to: { name: 'admin-categories' }, label: 'Категории', icon: FolderTree },
  { to: { name: 'admin-orders' },     label: 'Заказы',    icon: ClipboardList },
  { to: { name: 'admin-stock' },      label: 'Склад',     icon: Boxes },
  { to: { name: 'admin-errors' },     label: 'Ошибки',    icon: TriangleAlert },
]

const route  = useRoute()
const router = useRouter()

// A section stays active on its nested pages (create / edit)
const isActive = (link) => route.path.startsWith(router.resolve(link.to).path)
</script>
