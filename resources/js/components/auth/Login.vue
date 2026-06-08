<template>
  <div class="flex min-h-screen items-center justify-center px-4 py-8">
    <Card class="w-full max-w-md">
      <CardHeader class="space-y-2">
        <CardTitle class="text-2xl">{{ t('auth.login.title') }}</CardTitle>
        <CardDescription>{{ t('auth.login.subtitle') }}</CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="submit" class="space-y-4">
          <div class="space-y-2">
            <Label for="email">{{ t('auth.fields.email') }}</Label>
            <Input id="email" type="email" placeholder="name@example.com" v-model="form.email" required />
          </div>

          <div class="space-y-2">
            <Label for="password">{{ t('auth.fields.password') }}</Label>
            <Input id="password" type="password" placeholder="••••••••" v-model="form.password" required />
          </div>

          <label class="flex items-center gap-2 cursor-pointer text-sm">
            <input type="checkbox" class="h-4 w-4" />
            <span>{{ t('auth.login.remember') }}</span>
          </label>

          <Button type="submit" class="w-full" :disabled="isLoading">
            <LogIn class="h-4 w-4 mr-2" />
            {{ isLoading ? t('auth.login.submitting') : t('auth.login.submit') }}
          </Button>

          <div class="relative">
            <Separator />
            <span class="absolute inset-0 flex items-center justify-center">
              <span class="bg-card px-2 text-xs text-muted-foreground">{{ t('auth.or') }}</span>
            </span>
          </div>

          <Button variant="outline" type="button" class="w-full" disabled>
            <Chrome class="h-4 w-4 mr-2" />
            {{ t('auth.google') }}
          </Button>
        </form>
      </CardContent>

      <CardFooter class="flex-col gap-4">
        <Separator />
        <p class="text-sm text-center text-muted-foreground">
          {{ t('auth.login.noAccount') }}
          <RouterLink to="/register" class="text-primary hover:underline font-semibold">
            {{ t('auth.login.toRegister') }}
          </RouterLink>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/store/userStore.js';
import { useI18n } from '@/i18n';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { toast } from 'vue-sonner';
import { LogIn, Chrome } from 'lucide-vue-next';

const router = useRouter();
const userStore = useUserStore();
const { t } = useI18n();

const form = reactive({
  email: '',
  password: ''
});

const isLoading = ref(false);

const submit = async () => {
  isLoading.value = true;

  const success = await userStore.login(form);

  if (success) {
    toast.success(t('auth.login.success'));
    await router.push('/');
  } else {
    toast.error(userStore.errors.message || t('auth.login.error'));
  }

  isLoading.value = false;
};
</script>
