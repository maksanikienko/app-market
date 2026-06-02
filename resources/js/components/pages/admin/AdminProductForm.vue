<template>
  <div class="space-y-6 w-full">
    <div class="flex items-center gap-3">
      <Button variant="ghost" size="sm" @click="router.push('/admin/products')">← Назад</Button>
      <h1 class="text-2xl font-semibold">{{ id ? 'Редактировать товар' : 'Новый товар' }}</h1>
    </div>

    <div v-if="pageLoading" class="text-muted-foreground">Загрузка…</div>

    <template v-else>
      <form @submit.prevent="submit" class="space-y-6">

        <!-- Basic Info -->
        <Card>
          <CardHeader>
            <div class="flex items-center justify-between">
              <CardTitle>Основная информация</CardTitle>
              <div class="flex rounded-md border overflow-hidden text-sm">
                <button type="button"
                  :class="['px-3 py-1 font-medium transition-colors', editLocale === 'ro' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-muted']"
                  @click="editLocale = 'ro'">RO</button>
                <button type="button"
                  :class="['px-3 py-1 font-medium transition-colors', editLocale === 'ru' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-muted']"
                  @click="editLocale = 'ru'">RU</button>
              </div>
            </div>
          </CardHeader>
          <CardContent class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1">
                <Label>Название * <span class="text-xs text-muted-foreground uppercase">{{ editLocale }}</span></Label>
                <Input v-model="form.name[editLocale]" @input="syncSlug" />
                <FieldError :error="errors['name.ro'] || errors.name" />
              </div>
              <div class="space-y-1">
                <Label>Slug *</Label>
                <Input v-model="form.slug" @input="slugManuallyEdited = true" />
                <FieldError :error="errors.slug" />
              </div>
              <div class="space-y-1">
                <Label>Код (SKU) *</Label>
                <Input v-model="form.code" />
                <FieldError :error="errors.code" />
              </div>
              <div class="space-y-1">
                <Label>Бренд</Label>
                <Select v-model="form.brand_id">
                  <SelectTrigger><SelectValue placeholder="Выберите бренд" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Без бренда —</SelectItem>
                    <SelectItem v-for="b in brands" :key="b.id" :value="String(b.id)">{{ b.name }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :error="errors.brand_id" />
              </div>
              <div class="space-y-1">
                <Label>Категория</Label>
                <Select v-model="form.category_id">
                  <SelectTrigger><SelectValue placeholder="Выберите категорию" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Без категории —</SelectItem>
                    <SelectItem v-for="cat in categories" :key="cat.id" :value="String(cat.id)">{{ localeStore.t(cat.name) }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :error="errors.category_id" />
              </div>
            </div>

            <div class="space-y-1">
              <Label>Краткое описание <span class="text-xs text-muted-foreground uppercase">{{ editLocale }}</span></Label>
              <Input v-model="form.short_description[editLocale]" placeholder="Краткое описание товара (макс. 500 символов)" />
              <FieldError :error="errors['short_description.ro'] || errors.short_description" />
            </div>
            <div class="space-y-1">
              <Label>Описание <span class="text-xs text-muted-foreground uppercase">{{ editLocale }}</span></Label>
              <textarea
                v-model="form.description[editLocale]"
                rows="5"
                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
              />
              <FieldError :error="errors['description.ro'] || errors.description" />
            </div>
          </CardContent>
        </Card>

        <!-- Pricing & Status -->
        <Card>
          <CardHeader><CardTitle>Цена и статус</CardTitle></CardHeader>
          <CardContent class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1">
                <Label>Цена *</Label>
                <Input v-model="form.price" type="number" step="0.01" min="0" />
                <FieldError :error="errors.price" />
              </div>
              <div class="space-y-1">
                <Label>Старая цена (зачёркнутая)</Label>
                <Input v-model="form.old_price" type="number" step="0.01" min="0" placeholder="Оставьте пустым, если скидки нет" />
                <FieldError :error="errors.old_price" />
              </div>
            </div>

            <div class="grid grid-cols-4 gap-4 pt-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.is_active" class="rounded" />
                <span class="text-sm font-medium">Активен</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.is_new" class="rounded" />
                <span class="text-sm font-medium">Новинка</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.is_hit" class="rounded" />
                <span class="text-sm font-medium">Хит</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.is_sale" class="rounded" />
                <span class="text-sm font-medium">Скидка</span>
              </label>
            </div>
          </CardContent>
        </Card>

        <!-- Clothing Specs -->
        <Card>
          <CardHeader><CardTitle>Характеристики одежды</CardTitle></CardHeader>
          <CardContent class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1">
                <Label>Внешний материал</Label>
                <Select v-model="form.outer_material_id">
                  <SelectTrigger><SelectValue placeholder="Выберите материал" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Не указано —</SelectItem>
                    <SelectItem v-for="c in classifiers.outer_material" :key="c.id" :value="String(c.id)">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div class="space-y-1">
                <Label>Подкладка</Label>
                <Select v-model="form.lining_material_id">
                  <SelectTrigger><SelectValue placeholder="Выберите подкладку" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Не указано —</SelectItem>
                    <SelectItem v-for="c in classifiers.lining_material" :key="c.id" :value="String(c.id)">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div class="space-y-1">
                <Label>Наполнитель</Label>
                <Select v-model="form.filling_id">
                  <SelectTrigger><SelectValue placeholder="Выберите наполнитель" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Не указано —</SelectItem>
                    <SelectItem v-for="c in classifiers.filling" :key="c.id" :value="String(c.id)">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div class="space-y-1">
                <Label>Сезон</Label>
                <Select v-model="form.season">
                  <SelectTrigger><SelectValue placeholder="Выберите сезон" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="none">— Не указано —</SelectItem>
                    <SelectItem v-for="c in seasonOptions" :key="c.key" :value="c.key">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div class="space-y-1">
                <Label>Длина</Label>
                <Select v-model="form.length">
                  <SelectTrigger><SelectValue placeholder="Выберите длину" /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="none">— Не указано —</SelectItem>
                    <SelectItem v-for="c in lengthOptions" :key="c.key" :value="c.key">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.hood" class="rounded" />
                <span class="text-sm font-medium">Есть капюшон</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.detachable_hood" class="rounded" />
                <span class="text-sm font-medium">Съёмный капюшон</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.waterproof" class="rounded" />
                <span class="text-sm font-medium">Водонепроницаемый</span>
              </label>
            </div>
          </CardContent>
        </Card>

        <!-- Variants -->
        <Card>
          <CardHeader>
            <div class="flex items-center justify-between">
              <CardTitle>Варианты товара</CardTitle>
              <Button type="button" variant="outline" size="sm" @click="addVariant">+ Добавить вариант</Button>
            </div>
          </CardHeader>
          <CardContent>
            <p v-if="!form.variants.length" class="text-sm text-muted-foreground py-1">
              Нет вариантов — нажмите «+ Добавить вариант».
            </p>
            <div v-else class="space-y-2">
              <div
                v-for="(variant, i) in form.variants" :key="i"
                class="grid gap-2 items-end border rounded-md p-3"
                style="grid-template-columns: 1fr 90px 120px 100px 36px"
              >
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Цвет</Label>
                  <Select :model-value="variant.color_hex" @update:model-value="val => applyColor(variant, val)">
                    <SelectTrigger>
                      <div class="flex items-center gap-2 w-full overflow-hidden min-w-0">
                        <span v-if="variant.color_hex"
                              class="inline-block w-3.5 h-3.5 rounded-sm shrink-0 border border-black/10"
                              :style="`background:${variant.color_hex}`" />
                        <span class="truncate text-sm">{{ variant.color ? colorLabel(variant.color) : 'Выберите цвет' }}</span>
                      </div>
                    </SelectTrigger>
                    <SelectContent class="max-h-64">
                      <SelectItem v-for="c in COLORS" :key="c.hex" :value="c.hex">
                        <div class="flex items-center gap-2 w-full">
                          <span class="inline-block w-3.5 h-3.5 rounded-sm shrink-0 border border-black/10" :style="`background:${c.hex}`" />
                          <span>{{ localeStore.t(c.name) }}</span>
                          <span class="ml-2 text-[10px] text-muted-foreground font-mono">{{ c.hex }}</span>
                        </div>
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Размер</Label>
                  <Select v-model="variant.size">
                    <SelectTrigger class="w-full">
                      <SelectValue placeholder="Выберите размер" />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectItem
                          v-for="size in sizes"
                          :key="size"
                          :value="size"
                      >
                        {{ size }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Цена (перекр.)</Label>
                  <Input v-model="variant.price" type="number" step="0.01" min="0" placeholder="—" />
                </div>
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Остаток</Label>
                  <Input v-model="variant.stock" type="number" min="0" placeholder="0" />
                </div>
                <Button
                  type="button" variant="ghost" size="sm"
                  class="text-destructive hover:text-destructive px-2 self-end"
                  @click="removeVariant(i)"
                >✕</Button>
              </div>
            </div>
          </CardContent>
        </Card>

        <div class="flex gap-2">
          <Button type="submit" :disabled="saving">{{ saving ? 'Сохранение…' : 'Сохранить' }}</Button>
          <Button type="button" variant="outline" @click="router.push('/admin/products')">Отмена</Button>
        </div>
      </form>

      <!-- Image manager only in edit mode -->
      <div v-if="id">
        <ProductImagesManager :product-id="id" :initial-images="product?.media_items ?? []" />
      </div>
      <p v-else class="text-sm text-muted-foreground">Сначала сохраните товар, затем можно добавить изображения.</p>
    </template>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import ProductImagesManager from '@/components/admin/ProductImagesManager.vue'
import { useAdminProductService } from '@/services/adminProductService'
import { useProductService } from '@/services/productService'
import { useCategoryService } from '@/services/categoryService'
import { useClassifierService } from '@/services/classifierService'
import { useLocaleStore } from '@/store/localeStore'
import { COLORS } from '@/config/colors'

const props = defineProps({ id: { type: String, default: null } })

const router = useRouter()
const { create, update } = useAdminProductService()
const { getById } = useProductService()
const { getCategories } = useCategoryService()
const { getClassifiers, getBrands } = useClassifierService()
const localeStore = useLocaleStore()

const FieldError = { props: ['error'], template: `<p v-if="error" class="text-xs text-destructive mt-1">{{ error[0] }}</p>` }

const product = ref(null)
const categories = ref([])
const brands = ref([])
const classifiers = ref({})
const pageLoading = ref(false)
const saving = ref(false)
const errors = ref({})
const form = ref(empty())
const slugManuallyEdited = ref(false)
const editLocale = ref('ro')
const sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']

function colorLabel(colorJson) {
  if (!colorJson) return ''
  try {
    const obj = typeof colorJson === 'string' ? JSON.parse(colorJson) : colorJson
    return localeStore.t(obj)
  } catch {
    return colorJson
  }
}

function applyColor(variant, hex) {
  const c = COLORS.find(c => c.hex === hex)
  variant.color_hex = hex
  variant.color = c ? JSON.stringify(c.name) : ''
}

function empty() {
  return {
    name: { ro: '', ru: '' },
    slug: '', code: '', price: '', old_price: '',
    category_id: '0', brand_id: '0',
    description: { ro: '', ru: '' },
    short_description: { ro: '', ru: '' },
    is_active: true, is_new: false, is_hit: false, is_sale: false,
    outer_material_id: '0', lining_material_id: '0', filling_id: '0',
    season: 'none', length: 'none',
    hood: false, detachable_hood: false, waterproof: false,
    variants: [],
  }
}

function emptyVariant() {
  return { color: '', color_hex: '', size: '', sku: '', price: '', stock: 0 }
}

function addVariant() {
  form.value.variants.push(emptyVariant())
}

function removeVariant(i) {
  form.value.variants.splice(i, 1)
}

// computed options — fixes JS `classifiers.length` property conflict
const seasonOptions = computed(() => classifiers.value?.season ?? [])
const lengthOptions = computed(() => classifiers.value?.length ?? [])

function slugify(str) {
  return str.toLowerCase().trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
}

function syncSlug() {
  if (!slugManuallyEdited.value) {
    form.value.slug = slugify(form.value.name.ro)
  }
}

function nullableId(val) {
  const n = parseInt(val)
  return n > 0 ? String(n) : '0'
}

onMounted(async () => {
  pageLoading.value = true
  const [cats, cls, brs] = await Promise.all([
    getCategories(),
    getClassifiers(),
    getBrands(),
    props.id ? getById(props.id).then(p => {
      product.value = p
      slugManuallyEdited.value = true
      form.value = {
        name:               typeof p.name === 'object' ? { ro: p.name.ro ?? '', ru: p.name.ru ?? '' } : { ro: p.name ?? '', ru: '' },
        slug:               p.slug ?? '',
        code:               p.code ?? '',
        price:              String(p.price ?? ''),
        old_price:          p.old_price ? String(p.old_price) : '',
        category_id:        nullableId(p.category_id),
        brand_id:           nullableId(p.brand_id),
        description:        typeof p.description === 'object' ? { ro: p.description.ro ?? '', ru: p.description.ru ?? '' } : { ro: p.description ?? '', ru: '' },
        short_description:  typeof p.short_description === 'object' ? { ro: p.short_description.ro ?? '', ru: p.short_description.ru ?? '' } : { ro: p.short_description ?? '', ru: '' },
        is_active:          !!p.is_active,
        is_new:             !!p.is_new,
        is_hit:             !!p.is_hit,
        is_sale:            !!p.is_sale,
        outer_material_id:  nullableId(p.outer_material_id),
        lining_material_id: nullableId(p.lining_material_id),
        filling_id:         nullableId(p.filling_id),
        season:             p.season || 'none',
        length:             p.length || 'none',
        hood:               !!p.hood,
        detachable_hood:    !!p.detachable_hood,
        waterproof:         !!p.waterproof,
        variants: (p.variants ?? []).map(v => ({
          color:     v.color ?? '',
          color_hex: v.color_hex ?? '#000000',
          size:      v.size ?? '',
          sku:       v.sku ?? '',
          price:     v.price != null ? String(v.price) : '',
          stock:     v.stock ?? 0,
        })),
      }
    }) : Promise.resolve(),
  ])
  categories.value = cats
  classifiers.value = cls
  brands.value = brs
  pageLoading.value = false
})

async function submit() {
  saving.value = true
  errors.value = {}

  const payload = {
    ...form.value,
    category_id:        parseInt(form.value.category_id) || null,
    brand_id:           parseInt(form.value.brand_id) || null,
    outer_material_id:  parseInt(form.value.outer_material_id) || null,
    lining_material_id: parseInt(form.value.lining_material_id) || null,
    filling_id:         parseInt(form.value.filling_id) || null,
    old_price:          form.value.old_price || null,
    season:             form.value.season === 'none' ? null : form.value.season,
    length:             form.value.length === 'none' ? null : form.value.length,
    is_active:          form.value.is_active ? 1 : 0,
    is_new:             form.value.is_new ? 1 : 0,
    is_hit:             form.value.is_hit ? 1 : 0,
    is_sale:            form.value.is_sale ? 1 : 0,
    hood:               form.value.hood ? 1 : 0,
    detachable_hood:    form.value.detachable_hood ? 1 : 0,
    waterproof:         form.value.waterproof ? 1 : 0,
    variants: form.value.variants.map(v => ({
      color:     v.color || null,
      color_hex: v.color_hex || null,
      size:      v.size || null,
      sku:       v.sku || null,
      price:     v.price !== '' ? parseFloat(v.price) : null,
      stock:     parseInt(v.stock) || 0,
    })),
  }

  try {
    if (props.id) {
      await update(props.id, payload)
      router.push('/admin/products')
    } else {
      await create(payload)
      router.push('/admin/products')
    }
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors
  } finally {
    saving.value = false
  }
}
</script>