<template>
  <div class="space-y-6 w-full">
    <div class="flex items-center gap-3">
      <Button variant="ghost" size="sm" @click="router.push('/admin/categories')">← Назад</Button>
      <h1 class="text-2xl font-semibold">{{ id ? 'Редактировать категорию' : 'Новая категория' }}</h1>
    </div>

    <div v-if="pageLoading" class="text-muted-foreground">Загрузка…</div>

    <div v-else class="flex justify-center">
      <Card class="w-full max-w-3xl">
        <CardContent class="pt-6">
          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1">
                <Label>Название</Label>
                <Input v-model="form.name" />
                <FieldError :error="errors.name" />
              </div>

              <div class="space-y-1">
                <Label>Код</Label>
                <Input v-model="form.code" />
                <FieldError :error="errors.code" />
              </div>
            </div>

            <div class="space-y-1">
              <Label>Описание</Label>
              <textarea
                v-model="form.description"
                rows="5"
                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
              />
              <FieldError :error="errors.description" />
            </div>

            <div class="space-y-1">
              <Label>
                Изображение
                <span class="text-muted-foreground text-xs font-normal">
                  ({{ id ? 'оставьте пустым, чтобы сохранить текущее' : 'необязательно' }})
                </span>
              </Label>
              <img v-if="category?.image" :src="`/storage/${category.image}`" class="w-20 h-20 object-cover rounded" />
              <Input type="file" accept="image/*" @change="e => form.imageFile = e.target.files[0]" />
              <FieldError :error="errors.image" />
            </div>

            <div class="flex gap-2 pt-2">
              <Button type="submit" :disabled="saving">{{ saving ? 'Сохранение…' : 'Сохранить' }}</Button>
              <Button type="button" variant="outline" @click="router.push('/admin/categories')">Отмена</Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useAdminCategoryService } from '@/services/adminCategoryService'

const props = defineProps({ id: { type: String, default: null } })

const router = useRouter()
const { getById, create, update } = useAdminCategoryService()

const FieldError = { props: ['error'], template: `<p v-if="error" class="text-xs text-destructive">{{ error[0] }}</p>` }

const category = ref(null)
const pageLoading = ref(false)
const saving = ref(false)
const errors = ref({})
const form = ref(empty())

function empty() {
  return { name: '', code: '', description: '', imageFile: null }
}

onMounted(async () => {
  if (!props.id) return
  pageLoading.value = true
  try {
    const c = await getById(props.id)
    category.value = c
    form.value = { name: c.name, code: c.code, description: c.description, imageFile: null }
  } finally {
    pageLoading.value = false
  }
})

async function submit() {
  saving.value = true
  errors.value = {}
  const fd = new FormData()
  fd.append('name', form.value.name)
  fd.append('code', form.value.code)
  fd.append('description', form.value.description)
  if (form.value.imageFile) fd.append('image', form.value.imageFile)
  try {
    props.id ? await update(props.id, fd) : await create(fd)
    router.push('/admin/categories')
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors
  } finally {
    saving.value = false
  }
}
</script>
