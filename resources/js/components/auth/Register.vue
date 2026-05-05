<template>
  <div class="flex min-h-screen items-center justify-center px-4">
    <Card class="w-full max-w-md">
      <CardHeader class="space-y-2">
        <CardTitle class="text-2xl">Register</CardTitle>
        <CardDescription>Create new account</CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="submit" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <Label for="firstName">Name</Label>
              <Input id="firstName" placeholder="John" v-model="form.firstName" required />
            </div>
            <div class="space-y-2">
              <Label for="lastName">Surname</Label>
              <Input id="lastName" placeholder="Doe" v-model="form.lastName" required />
            </div>
          </div>

          <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input id="email" type="email" placeholder="name@example.com" v-model="form.email" required />
          </div>

          <div class="space-y-2">
            <Label for="password">Password</Label>
            <Input id="password" type="password" placeholder="••••••••" v-model="form.password" required />
            <p class="text-xs text-muted-foreground">Min 8 symbols</p>
          </div>

          <div class="space-y-2">
            <Label for="confirmPassword">Repeat password</Label>
            <Input id="confirmPassword" type="password" placeholder="••••••••" v-model="form.confirmPassword" required />
          </div>

          <Button type="submit" class="w-full">
            <UserPlus class="h-4 w-4 mr-2" />
            Create account
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
          Already have an account?
          <RouterLink to="/login" class="text-primary hover:underline font-semibold">
            Log in
          </RouterLink>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { UserPlus, Github, Chrome } from 'lucide-vue-next';

const router = useRouter()

const form = reactive({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  confirmPassword: '',
  agreeTerms: ''
})

const submit = async () => {
  try {
    await axios.post('/register', form)
    await router.push('/orders')
  } catch (e) {
    console.log(e.response.data)
  }
}
</script>