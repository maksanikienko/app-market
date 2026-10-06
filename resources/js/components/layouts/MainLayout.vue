<template>
  <SidebarProvider>
    <AppSidebar />

    <SidebarInset class="min-w-0 md:peer-data-[variant=inset]:shadow-[0_1px_2px_rgb(0_0_0/0.04),0_8px_32px_-12px_rgb(60_40_20/0.12)]">
      <Header />

      <main class="flex-1 w-full max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 py-6 lg:py-10">
        <RouterView v-slot="{ Component, route: r }">
          <Transition name="page" mode="out-in">
            <component :is="Component" :key="r.path" />
          </Transition>
        </RouterView>
      </main>

      <footer class="border-t px-4 sm:px-6 lg:px-10 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-muted-foreground">
        <p>© {{ year }} FORYOU. {{ t('footer.rights') }}</p>
        <p class="font-display italic text-sm">{{ t('footer.madeFor') }}</p>
      </footer>
    </SidebarInset>
  </SidebarProvider>
</template>

<script setup>
import { SidebarProvider, SidebarInset } from '@/components/ui/sidebar';
import AppSidebar from '@/components/layouts/AppSidebar.vue';
import Header from '@/components/layouts/Header.vue';
import { useI18n } from '@/i18n';

const { t } = useI18n();
const year  = new Date().getFullYear();
</script>
