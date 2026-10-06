<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" size="icon" class="rounded-full p-0 transition-transform duration-300 ease-spring hover:scale-105 data-[state=open]:ring-2 data-[state=open]:ring-brand/40" :aria-label="user?.name ?? t('nav.login')">
        <Avatar class="size-8">
          <AvatarImage v-if="user?.avatar" :src="user.avatar" :alt="user.name" />
          <AvatarFallback class="bg-brand/15 text-xs font-semibold text-brand">
            <UserRound v-if="!user" class="size-4" />
            <template v-else>{{ initials }}</template>
          </AvatarFallback>
        </Avatar>
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent align="end" :side-offset="8" class="w-60 rounded-xl">
      <DropdownMenuLabel class="flex items-center gap-3 py-2 font-normal">
        <Avatar class="size-9">
          <AvatarImage v-if="user?.avatar" :src="user.avatar" :alt="user.name" />
          <AvatarFallback class="bg-brand/15 text-xs font-semibold text-brand">
            <UserRound v-if="!user" class="size-4" />
            <template v-else>{{ initials }}</template>
          </AvatarFallback>
        </Avatar>
        <div class="grid min-w-0 leading-tight">
          <span class="truncate text-sm font-medium">{{ user?.name ?? t('nav.guest') }}</span>
          <span class="truncate text-xs text-muted-foreground">{{ user?.email ?? t('nav.guestHint') }}</span>
        </div>
      </DropdownMenuLabel>
      <DropdownMenuSeparator />

      <template v-if="user">
        <DropdownMenuItem @click="go('profile')"><Package /> {{ t('nav.profile') }}</DropdownMenuItem>
        <DropdownMenuItem v-if="userStore.isAdmin" @click="go('admin-orders')"><ShieldCheck /> {{ t('nav.admin') }}</DropdownMenuItem>
        <DropdownMenuSeparator />
        <DropdownMenuItem class="text-destructive focus:text-destructive" @click="logout"><LogOut class="text-destructive" /> {{ t('nav.logout') }}</DropdownMenuItem>
      </template>
      <template v-else>
        <DropdownMenuItem @click="go('login')"><LogIn /> {{ t('nav.login') }}</DropdownMenuItem>
        <DropdownMenuItem @click="go('register')"><UserPlus /> {{ t('nav.register') }}</DropdownMenuItem>
      </template>
    </DropdownMenuContent>
  </DropdownMenu>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { LogIn, LogOut, Package, ShieldCheck, UserPlus, UserRound } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
  DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useUserStore } from '@/store/userStore.js';
import { useI18n } from '@/i18n';

const router    = useRouter();
const userStore = useUserStore();
const { t }     = useI18n();

const user     = computed(() => userStore.user);
const initials = computed(() =>
  (user.value?.name ?? '?').split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2)
);

const go = (name) => router.push({ name });

const logout = async () => {
  await userStore.logout();
  go('login');
};
</script>
