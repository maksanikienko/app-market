<template>
  <Sidebar variant="inset" collapsible="offcanvas" class="select-none">
    <SidebarHeader>
      <RouterLink :to="{ name: 'home' }" class="group/logo flex items-center gap-3 rounded-xl px-2 py-2 transition-colors hover:bg-sidebar-accent">
        <span class="grid size-9 place-items-center rounded-xl bg-primary text-primary-foreground font-display text-xl italic shadow-sm transition-transform duration-500 ease-spring group-hover/logo:rotate-[-8deg] group-hover/logo:scale-105">
          F
        </span>
        <span class="flex flex-col leading-none">
          <span class="text-sm font-semibold tracking-[0.3em]">FORYOU</span>
          <span class="mt-1 text-[11px] text-muted-foreground">{{ t('nav.tagline') }}</span>
        </span>
      </RouterLink>
    </SidebarHeader>

    <!-- ScrollArea takes over scrolling so the sidebar gets a slim overlay scrollbar -->
    <SidebarContent class="overflow-hidden">
      <ScrollArea class="min-h-0 flex-1">
        <div class="flex flex-col gap-2">
          <!-- Main navigation -->
          <SidebarGroup>
            <SidebarGroupLabel>{{ t('nav.menu') }}</SidebarGroupLabel>
            <SidebarMenu>
              <SidebarMenuItem v-for="link in NAV_LINKS" :key="link.name">
                <SidebarMenuButton as-child :is-active="route.name === link.name" @click="closeMobile">
                  <RouterLink :to="{ name: link.name }">
                    <component :is="link.icon" />
                    <span>{{ t(link.label) }}</span>
                  </RouterLink>
                </SidebarMenuButton>
                <SidebarMenuBadge v-if="link.name === 'cart' && cartStore.count" :key="cartStore.count" class="animate-pop rounded-full bg-brand text-brand-foreground">
                  {{ cartStore.count }}
                </SidebarMenuBadge>
              </SidebarMenuItem>
            </SidebarMenu>
          </SidebarGroup>

          <!-- Categories -->
          <Collapsible default-open class="group/collapsible">
            <SidebarGroup>
              <SidebarGroupLabel as-child>
                <CollapsibleTrigger class="w-full">
                  {{ t('nav.categories') }}
                  <ChevronDown class="ml-auto transition-transform duration-300 group-data-[state=open]/collapsible:rotate-180" />
                </CollapsibleTrigger>
              </SidebarGroupLabel>

              <CollapsibleContent class="collapsible-content">
                <SidebarMenu>
                  <template v-if="categoryStore.isLoading">
                    <SidebarMenuItem v-for="i in 5" :key="i"><SidebarMenuSkeleton show-icon /></SidebarMenuItem>
                  </template>

                  <SidebarMenuItem v-for="(cat, i) in categories" :key="cat.id" class="animate-rise stagger" :style="{ '--i': i }">
                    <SidebarMenuButton size="lg" :is-active="isActiveCategory(cat.id)" class="h-11" @click="openCategory(cat.id)">
                      <img
                        :src="`/storage/categories/${cat.slug}.png`"
                        :alt="localeStore.t(cat.name)"
                        class="size-8 shrink-0 rounded-lg object-cover ring-1 ring-sidebar-border"
                      />
                      <span>{{ localeStore.t(cat.name) }}</span>
                      <ChevronRight class="ml-auto size-3.5! opacity-0 -translate-x-1 transition-all duration-300 group-hover/menu-item:opacity-60 group-hover/menu-item:translate-x-0" />
                    </SidebarMenuButton>
                  </SidebarMenuItem>
                </SidebarMenu>
              </CollapsibleContent>
            </SidebarGroup>
          </Collapsible>

          <!-- Catalog filters -->
          <Transition
            enter-active-class="transition duration-500 ease-out-expo"
            enter-from-class="opacity-0 -translate-x-3"
            leave-active-class="transition duration-150"
            leave-to-class="opacity-0"
          >
            <SidebarFilters v-if="route.name === 'products'" />
          </Transition>
        </div>
      </ScrollArea>
    </SidebarContent>

    <SidebarRail />
  </Sidebar>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { House, LayoutGrid, ShoppingBag, MapPin, ChevronDown, ChevronRight } from 'lucide-vue-next';
import {
  Sidebar, SidebarContent, SidebarGroup, SidebarGroupLabel, SidebarHeader,
  SidebarMenu, SidebarMenuBadge, SidebarMenuButton, SidebarMenuItem, SidebarMenuSkeleton, SidebarRail, useSidebar,
} from '@/components/ui/sidebar';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import SidebarFilters from '@/components/parts/SidebarFilters.vue';
import { useCategoryStore } from '@/store/categoryStore.js';
import { useFilterStore } from '@/store/filterStore.js';
import { useCartStore } from '@/store/cartStore.js';
import { useLocaleStore } from '@/store/localeStore.js';
import { useI18n } from '@/i18n';

const NAV_LINKS = [
  { name: 'home',     label: 'nav.home',     icon: House },
  { name: 'products', label: 'nav.products', icon: LayoutGrid },
  { name: 'cart',     label: 'nav.cart',     icon: ShoppingBag },
  { name: 'contact',  label: 'nav.contact',  icon: MapPin },
];

const route         = useRoute();
const router        = useRouter();
const categoryStore = useCategoryStore();
const filterStore   = useFilterStore();
const cartStore     = useCartStore();
const localeStore   = useLocaleStore();
const { t }         = useI18n();
const { isMobile, setOpenMobile } = useSidebar();
const { categories } = storeToRefs(categoryStore);

const closeMobile = () => isMobile.value && setOpenMobile(false);

const isActiveCategory = (id) =>
  route.name === 'products' && filterStore.selectedCategories.length === 1 && filterStore.selectedCategories[0] === id;

const openCategory = (id) => {
  filterStore.selectCategory(id);
  if (route.name !== 'products') router.push({ name: 'products' });
  closeMobile();
};

onMounted(() => {
  categoryStore.load();
  cartStore.fetchCart();
});
</script>
