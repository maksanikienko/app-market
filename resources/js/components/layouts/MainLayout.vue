<template>
  <div class="min-h-screen flex flex-col">
    <Header />

    <div class="flex flex-1">
      <AsidePanel v-if="showAside" />

      <main class="flex-1 container mx-auto px-4 py-6">
        <RouterView v-slot="{ Component, route: r }">
          <Transition :name="r.path === '/' ? 'fade' : 'slide'" mode="out-in">
            <component :is="Component" :key="r.path" />
          </Transition>
        </RouterView>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Header from '@/components/layouts/Header.vue';
import AsidePanel from '@/components/layouts/AsidePanel.vue';

const route     = useRoute();
const showAside = computed(() => route.path !== '/');
</script>

<style scoped>
/* Fade — used on the home page */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Slide-up — used on all other pages */
.slide-enter-active,
.slide-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.slide-enter-from {
  opacity: 0;
  transform: translateY(10px);
}
.slide-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
