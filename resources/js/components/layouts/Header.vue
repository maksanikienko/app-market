<template>
  <header class="glass sticky top-0 z-30 flex h-16 shrink-0 items-center gap-2 border-b px-4 sm:px-6 md:rounded-t-xl select-none">
    <Tooltip>
      <TooltipTrigger as-child>
        <SidebarTrigger class="-ml-1 rounded-full" />
      </TooltipTrigger>
      <TooltipContent side="bottom">{{ t('nav.toggleSidebar') }} <kbd class="ml-1 opacity-60">Ctrl B</kbd></TooltipContent>
    </Tooltip>
    <Separator orientation="vertical" class="mr-1 h-5!" />

    <Breadcrumb class="hidden min-w-0 sm:block">
      <BreadcrumbList class="flex-nowrap">
        <BreadcrumbItem>
          <BreadcrumbLink v-if="crumbs.length" as-child><RouterLink :to="{ name: 'home' }">{{ t('nav.home') }}</RouterLink></BreadcrumbLink>
          <BreadcrumbPage v-else>{{ t('nav.home') }}</BreadcrumbPage>
        </BreadcrumbItem>
        <template v-for="(crumb, i) in crumbs" :key="crumb.label">
          <BreadcrumbSeparator />
          <BreadcrumbItem class="min-w-0">
            <BreadcrumbLink v-if="crumb.to && i < crumbs.length - 1" as-child><RouterLink :to="crumb.to">{{ t(crumb.label) }}</RouterLink></BreadcrumbLink>
            <BreadcrumbPage v-else class="truncate">{{ t(crumb.label) }}</BreadcrumbPage>
          </BreadcrumbItem>
        </template>
      </BreadcrumbList>
    </Breadcrumb>

    <div class="ml-auto flex items-center gap-1">
      <ToggleGroup
        type="single"
        size="sm"
        class="mr-1 rounded-full bg-muted p-0.5"
        :model-value="localeStore.current"
        @update:model-value="value => value && localeStore.setLocale(value)"
      >
        <ToggleGroupItem
          v-for="locale in LOCALES"
          :key="locale"
          :value="locale"
          class="h-7 rounded-full! px-2.5 text-[11px] font-semibold uppercase tracking-wider text-muted-foreground transition-all data-[state=on]:bg-background data-[state=on]:text-foreground data-[state=on]:shadow-sm"
        >{{ locale }}</ToggleGroupItem>
      </ToggleGroup>

      <ThemeToggle />

      <Button variant="ghost" size="icon" class="relative rounded-full" as-child>
        <RouterLink :to="{ name: 'cart' }" :aria-label="t('nav.cart')">
          <ShoppingBag />
          <span
            v-if="cartStore.count"
            :key="cartStore.count"
            class="animate-pop absolute -top-0.5 -right-0.5 grid h-4.5 min-w-4.5 place-items-center rounded-full bg-brand px-1 text-[10px] font-semibold leading-none text-brand-foreground ring-2 ring-background"
          >{{ cartStore.count }}</span>
        </RouterLink>
      </Button>

      <Separator orientation="vertical" class="mx-1 hidden h-5! sm:block" />
      <UserMenu />
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { ShoppingBag } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbPage, BreadcrumbSeparator } from '@/components/ui/breadcrumb';
import ThemeToggle from '@/components/parts/ThemeToggle.vue';
import UserMenu from '@/components/parts/UserMenu.vue';
import { useLocaleStore } from '@/store/localeStore.js';
import { useCartStore } from '@/store/cartStore.js';
import { useI18n } from '@/i18n';

const LOCALES = ['ru', 'ro'];

const route       = useRoute();
const localeStore = useLocaleStore();
const cartStore   = useCartStore();
const { t }       = useI18n();

const crumbs = computed(() =>
  (route.meta.crumbs ?? []).map(crumb => (typeof crumb === 'string' ? { label: crumb } : crumb))
);
</script>
