<template>
  <Collapsible :default-open="defaultOpen" class="group/collapsible">
    <SidebarGroup class="py-0.5">
      <SidebarGroupLabel as-child class="h-9 text-sm font-medium text-sidebar-foreground hover:bg-sidebar-accent">
        <CollapsibleTrigger class="w-full">
          {{ title }}
          <ChevronDown class="ml-auto text-muted-foreground transition-transform duration-300 group-data-[state=open]/collapsible:rotate-180" />
        </CollapsibleTrigger>
      </SidebarGroupLabel>
      <CollapsibleContent class="collapsible-content">
        <SidebarGroupContent class="px-2 pt-2 pb-3">
          <slot />
        </SidebarGroupContent>
      </CollapsibleContent>
    </SidebarGroup>
  </Collapsible>
</template>

<script setup>
import { ChevronDown } from 'lucide-vue-next';
import { SidebarGroup, SidebarGroupContent, SidebarGroupLabel } from '@/components/ui/sidebar';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';

defineProps({
  title:       { type: String, required: true },
  defaultOpen: { type: Boolean, default: false },
});
</script>
