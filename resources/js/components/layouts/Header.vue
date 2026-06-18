<template>
  <header class="sticky top-0 z-50 bg-stone-100 border-b border-stone-200">
    <div class="max-w-screen-xl mx-auto px-6 flex h-16 items-center justify-between">

      <!-- Logo -->
      <RouterLink
        to="/"
        class="text-xl tracking-[0.3em] uppercase text-stone-900 hover:text-stone-500 transition-colors"
        style="font-family: var(--font-display); font-weight: 300;"
      >
        FORYOU
      </RouterLink>

      <!-- Nav -->
      <nav class="hidden md:flex gap-8">
        <RouterLink
          to="/"
          class="text-sm text-stone-500 hover:text-stone-900 transition-colors"
          :class="{ 'text-stone-900 font-medium': $route.path === '/' }"
        >{{ t('nav.home') }}</RouterLink>
        <RouterLink
          to="/products"
          class="text-sm text-stone-500 hover:text-stone-900 transition-colors"
          :class="{ 'text-stone-900 font-medium': $route.path === '/products' }"
        >{{ t('nav.products') }}</RouterLink>
        <RouterLink
          to="/contact"
          class="text-sm text-stone-500 hover:text-stone-900 transition-colors"
          :class="{ 'text-stone-900 font-medium': $route.path === '/contact' }"
        >{{ t('nav.contact') }}</RouterLink>
      </nav>

      <!-- Actions -->
      <div class="flex items-center gap-1">

        <!-- Language switcher -->
        <div class="flex items-center text-[11px] font-medium tracking-wider mr-2 border border-stone-200 rounded-md overflow-hidden">
          <button
            @click="localeStore.setLocale('ru')"
            :class="['px-2.5 py-1.5 transition-colors', localeStore.current === 'ru' ? 'bg-stone-800 text-white' : 'text-stone-500 hover:bg-stone-50']"
          >RU</button>
          <button
            @click="localeStore.setLocale('ro')"
            :class="['px-2.5 py-1.5 transition-colors border-l border-stone-200', localeStore.current === 'ro' ? 'bg-stone-800 text-white' : 'text-stone-500 hover:bg-stone-50']"
          >RO</button>
        </div>

        <!-- Cart -->
        <RouterLink to="/cart" class="relative p-2 rounded-lg hover:bg-stone-100 transition-colors">
          <ShoppingCart class="h-5 w-5 text-stone-700" />
          <span
            v-if="cartStore.count > 0"
            class="absolute top-0.5 right-0.5 h-4 w-4 bg-stone-900 text-white text-[9px] rounded-full flex items-center justify-center font-semibold leading-none"
          >{{ cartStore.count }}</span>
        </RouterLink>

        <!-- User -->
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <button class="p-1 rounded-full hover:ring-2 hover:ring-stone-200 transition-all">
              <Avatar class="h-8 w-8">
                <AvatarImage v-if="user?.avatar" :src="user.avatar" :alt="user.name" />
                <AvatarFallback>{{ initials }}</AvatarFallback>
              </Avatar>
            </button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" class="w-48">
            <div v-if="user" class="px-3 py-2 border-b border-stone-100">
              <p class="text-xs text-stone-500 truncate">{{ user.email || user.name }}</p>
            </div>
            <DropdownMenuItem v-if="!user" @click="navigateTo('/login')">{{ t('nav.login') }}</DropdownMenuItem>
            <DropdownMenuItem v-if="!user" @click="navigateTo('/register')">{{ t('nav.register') }}</DropdownMenuItem>
            <DropdownMenuItem v-if="user" @click="navigateTo('/profile')">{{ t('nav.profile') }}</DropdownMenuItem>
            <DropdownMenuItem v-if="userStore.isAdmin" @click="navigateTo('/admin/orders')" class="text-stone-600">
              {{ t('nav.admin') }}
            </DropdownMenuItem>
            <DropdownMenuSeparator v-if="user" />
            <DropdownMenuItem v-if="user" @click="handleLogout" class="text-stone-500">{{ t('nav.logout') }}</DropdownMenuItem>
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
import { useLocaleStore } from './../../store/localeStore.js';
import { useI18n } from '@/i18n';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import { ShoppingCart } from 'lucide-vue-next';
import { useCartStore } from './../../store/cartStore.js';

const router = useRouter();
const userStore = useUserStore();
const localeStore = useLocaleStore();
const { t } = useI18n();
const user = computed(() => userStore.user);
const initials = computed(() => {
  if (!user.value?.name) return '?'
  return user.value.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2)
});
const cartStore = useCartStore();
cartStore.fetchCart();

const navigateTo = (path) => router.push(path);
const handleLogout = async () => {
  await userStore.logout();
  await router.push('/login');
};
</script>