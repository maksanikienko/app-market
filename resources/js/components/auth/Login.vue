<template>
  <div class="flex min-h-screen items-center justify-center min-w-lg">
    <Card class="w-full ">
      <CardHeader class="space-y-2">
        <CardTitle class="text-2xl">Login</CardTitle>
        <CardDescription>Enter personal data</CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="submit" class="space-y-4">
          <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input id="email" type="email" placeholder="name@example.com" v-model="form.email" required />
          </div>

          <div class="space-y-2">
            <Label for="password">Password</Label>
            <Input id="password" type="password" placeholder="••••••••" v-model="form.password" required />
          </div>

          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" class="h-4 w-4" />
              <span>Remember me</span>
            </label>
            <RouterLink to="/" class="text-primary hover:underline">Forgot password?</RouterLink>
          </div>

          <Button type="submit" class="w-full" >
            <LogIn class="h-4 w-4 mr-2" />
            Submit
          </Button>

          <Separator />

          <Button variant="outline" type="button" class="w-full" disabled>
            <Chrome class="h-4 w-4 mr-2" />
            Google
          </Button>
        </form>
      </CardContent>

      <CardFooter class="flex-col gap-4">
        <Separator />
        <p class="text-sm text-center text-muted-foreground">
          Don't have an account?
          <RouterLink to="/register" class="text-primary hover:underline font-semibold">
            Register
          </RouterLink>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/store/userStore.js';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { toast } from 'vue-sonner';
import { LogIn, Github, Chrome } from 'lucide-vue-next';

const router = useRouter();
const userStore = useUserStore();

const form = reactive({
  email: '',
  password: ''
});

const errors = ref({});
const isLoading = ref(false);

const submit = async () => {
  isLoading.value = true;
  errors.value = {};

  const success = await userStore.login(form);

  if (success) {
    await router.push('/');
  } else {
    errors.value = userStore.errors || { general: 'Invalid email or password' };
    toast.error(userStore.errors.message || 'Invalid email or password');
  }

  isLoading.value = false;
};
</script>
