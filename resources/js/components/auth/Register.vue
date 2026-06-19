<template>
  <div class="flex min-h-screen items-center justify-center px-4 py-8 select-none">
    <Card class="w-full max-w-md">
      <CardHeader class="space-y-2">
        <RouterLink to="/" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 transition-colors w-fit">
          <ArrowLeft class="h-3.5 w-3.5" />
          Home
        </RouterLink>
        <CardTitle class="text-2xl">{{ t('auth.register.title') }}</CardTitle>
        <CardDescription>{{ t('auth.register.subtitle') }}</CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="submit" class="space-y-4">
          <div class="space-y-2">
            <div class="space-y-2">
              <Label for="name">{{ t('auth.fields.name') }}</Label>
              <Input id="name" :placeholder="t('auth.fields.name')" v-model="form.firstName" required />
            </div>
<!--            <div class="space-y-2">-->
<!--              <Label for="lastName">{{ t('auth.fields.surname') }}</Label>-->
<!--              <Input id="lastName" placeholder="Doe" v-model="form.lastName" required />-->
<!--            </div>-->
          </div>

          <div class="space-y-2">
            <Label for="email">{{ t('auth.fields.email') }}</Label>
            <Input id="email" type="email" :placeholder="t('auth.fields.email')" v-model="form.email" required />
          </div>

          <div class="space-y-2">
            <Label for="password">{{ t('auth.fields.password') }}</Label>
            <Input id="password" type="password" placeholder="••••••••" v-model="form.password" required />
            <p class="text-xs text-muted-foreground">{{ t('auth.fields.passwordHint') }}</p>
          </div>

          <div class="space-y-2">
            <Label for="confirmPassword">{{ t('auth.fields.confirmPassword') }}</Label>
            <Input id="confirmPassword" type="password" placeholder="••••••••" v-model="form.confirmPassword" required />
          </div>

          <Button type="submit" class="w-full" :disabled="isLoading">
            <UserPlus class="h-4 w-4 mr-2" />
            {{ isLoading ? t('auth.register.submitting') : t('auth.register.submit') }}
          </Button>

<!--          <div class="relative">-->
<!--            <Separator />-->
<!--            <span class="absolute inset-0 flex items-center justify-center">-->
<!--              <span class="bg-card px-2 text-xs text-muted-foreground">{{ t('auth.or') }}</span>-->
<!--            </span>-->
<!--          </div>-->

<!--          <Button variant="outline" type="button" class="w-full" disabled>-->
<!--            <Chrome class="h-4 w-4 mr-2" />-->
<!--            {{ t('auth.google') }}-->
<!--          </Button>-->
        </form>
      </CardContent>

      <CardFooter class="flex-col gap-4">
        <Separator />
        <p class="text-sm text-center text-muted-foreground">
          {{ t('auth.register.haveAccount') }}
          <RouterLink to="/login" class="text-primary hover:underline font-semibold">
            {{ t('auth.register.toLogin') }}
          </RouterLink>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/store/userStore.js'
import { useI18n } from '@/i18n'
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { toast } from 'vue-sonner';
import { UserPlus, Github, Chrome, ArrowLeft } from 'lucide-vue-next';

const router = useRouter()
const userStore = useUserStore()
const { t } = useI18n()

const form = reactive({
  firstName: '',
  // lastName: '',
  email: '',
  password: '',
  confirmPassword: ''
})

const isLoading = ref(false)

const submit = async () => {
  try {
    isLoading.value = true

    const success = await userStore.register({
      name: form.firstName.trim(),
      email: form.email,
      password: form.password,
      password_confirmation: form.confirmPassword
    })

    if (success) {
      toast.success(t('auth.register.success'))
      await router.push('/')
    } else {
      toast.error(userStore.errors.message || t('auth.register.error'))
    }
  } finally {
    isLoading.value = false
  }
}
</script>