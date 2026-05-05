<template>
  <header class="sticky top-0 z-50 border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container mx-auto flex h-14 items-center justify-between px-4">
<!--      <RouterLink to="/" class="flex items-center gap-2 font-semibold">-->
<!--        <div class="h-6 w-6 rounded bg-primary"></div>-->
<!--        <span class="hidden sm:inline">Store</span>-->
<!--      </RouterLink>-->

      <nav class="hidden md:flex gap-6">
        <RouterLink to="/" class="text-sm hover:text-primary transition-colors">Home</RouterLink>
        <RouterLink to="/products" class="text-sm hover:text-primary transition-colors">Products</RouterLink>
      </nav>

      <div class="flex items-center gap-2">
        <Button variant="ghost" size="icon">
          <Search class="h-4 w-4" />
        </Button>

        <RouterLink to="/cart" class="relative">
          <Button variant="ghost" size="icon">
            <ShoppingCart class="h-4 w-4" />
          </Button>
          <Badge v-if="cartStore.count > 0" class="absolute -top-1 -right-1 h-5 w-5 p-0 text-xs flex items-center justify-center">
            {{ cartStore.count }}
          </Badge>
        </RouterLink>

        <div v-if="user" class="hidden text-sm md:block mr-2 text-muted-foreground">
          {{ user.email || user.name }}
        </div>

        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon">
              <User class="h-4 w-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuItem v-if="!user" @click="navigateTo('/login')">Log in</DropdownMenuItem>
            <DropdownMenuItem v-if="!user" @click="navigateTo('/register')">Register</DropdownMenuItem>
            <DropdownMenuSeparator v-if="!user" />
            <DropdownMenuItem v-if="user" @click="navigateTo('/profile')">
              <span class="font-medium">{{ user.name || user.email }}</span>
            </DropdownMenuItem>
            <DropdownMenuItem v-if="userStore.isAdmin" @click="navigateTo('/admin/orders')">
              Admin panel
            </DropdownMenuItem>
            <DropdownMenuSeparator v-if="user" />
            <DropdownMenuItem v-if="user" @click="handleLogout">Exit</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from './../../store/userStore.js';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { Search, ShoppingCart, User } from 'lucide-vue-next';
import {useCartStore} from "./../../store/cartStore.js";

const router = useRouter();
const userStore = useUserStore();
const user = computed(() => userStore.user);

const cartStore = useCartStore();
cartStore.fetchCart();

const navigateTo = (path) => {
  router.push(path);
};

const handleLogout = async () => {
  await userStore.logout();
  await router.push('/login');
};
</script>
