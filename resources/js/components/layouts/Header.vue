<template>
  <header class="sticky top-0 z-50 bg-stone-100 border-b border-stone-200 select-none">
    <div class="max-w-screen-xl mx-auto px-6 flex h-16 items-center justify-between">

      <!-- Logo -->
      <RouterLink
        to="/"
        class="font-display font-light text-xl tracking-[0.3em] uppercase text-stone-900 hover:text-stone-500 transition-colors"
      >
        FORYOU
      </RouterLink>

      <!-- Nav -->
      <nav class="hidden md:flex gap-8">
        <RouterLink
          v-for="link in NAV_LINKS"
          :key="link.to"
          :to="link.to"
          class="text-sm text-stone-500 hover:text-stone-900 transition-colors"
          :class="{ 'text-stone-900 font-medium': $route.path === link.to }"
        >{{ t(link.label) }}</RouterLink>
      </nav>

      <!-- Actions -->
      <div class="flex items-center gap-1">

        <!-- Language switcher -->
        <div class="flex items-center text-[11px] font-medium tracking-wider mr-2 border border-stone-200 rounded-md overflow-hidden divide-x divide-stone-200">
          <button
            v-for="locale in LOCALES"
            :key="locale"
            @click="localeStore.setLocale(locale)"
            :class="['px-2.5 py-1.5 uppercase transition-colors', localeStore.current === locale ? 'bg-stone-800 text-white' : 'text-stone-500 hover:bg-stone-50']"
          >{{ locale }}</button>
        </div>

        <!-- Cart -->
        <RouterLink to="/cart" class="relative p-2 rounded-full hover:ring-2 hover:ring-stone-200 transition-all cursor-default">
          <ShoppingCart class="h-5 w-5 text-stone-700" />
          <span
            v-if="cartStore.count > 0"
            class="absolute top-0.5 right-0.5 h-4 w-4 bg-stone-900 text-white text-[9px] rounded-full flex items-center justify-center font-semibold leading-none"
          >{{ cartStore.count }}</span>
        </RouterLink>

        <!-- User -->
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <button v-if="user" class="p-2 rounded-full hover:ring-2 hover:ring-stone-200 transition-all">
              <Avatar class="h-8 w-8">
                <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name" />
                <AvatarFallback>{{ initials }}</AvatarFallback>
              </Avatar>
            </button>
            <button v-else class="p-2 rounded-full hover:ring-2 hover:ring-stone-200 transition-all">
              <User class=" h-5 w-5 text-stone-700" />
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
import { useUserStore } from '@/store/userStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useCartStore } from '@/store/cartStore.js';
import { useI18n } from '@/i18n';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import { ShoppingCart, User } from 'lucide-vue-next';

const NAV_LINKS = [
  { to: '/',         label: 'nav.home' },
  { to: '/products', label: 'nav.products' },
  { to: '/contact',  label: 'nav.contact' },
];
const LOCALES = ['ru', 'ro'];

const router      = useRouter();
const userStore   = useUserStore();
const localeStore = useLocaleStore();
const cartStore   = useCartStore();
const { t }       = useI18n();

const user = computed(() => userStore.user);
const initials = computed(() => {
  if (!user.value?.name) return '?'
  return user.value.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2)
});

cartStore.fetchCart();

const navigateTo = (path) => router.push(path);
const handleLogout = async () => {
  await userStore.logout();
  await router.push('/login');
};
</script>