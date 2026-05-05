<template>
  <div class="container mx-auto py-8 px-4">
    <h1 class="text-2xl font-semibold mb-4">Profile</h1>

    <div v-if="user" class="space-y-4 max-w-md">
      <div class="space-y-1">
        <p class="text-sm text-muted-foreground">Name</p>
        <p class="text-base font-medium">{{ user.name }}</p>
      </div>

      <div class="space-y-1">
        <p class="text-sm text-muted-foreground">Email</p>
        <p class="text-base font-medium">{{ user.email }}</p>
      </div>
    </div>

    <p v-else class="text-sm text-muted-foreground">
      User data is not available.
    </p>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useUserStore } from '@/store/userStore.js'

const userStore = useUserStore()
const user = computed(() => userStore.user)

onMounted(() => {
  if (!user.value) {
    userStore.fetchUser()
  }
})
</script>

