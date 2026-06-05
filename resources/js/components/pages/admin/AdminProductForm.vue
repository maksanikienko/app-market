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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1">
                <Label>Название * <span class="text-xs text-muted-foreground uppercase">{{ editLocale }}</span></Label>
                <Input
                  v-model="form.name[editLocale]"
                  @input="syncSlug"
                  :class="inputClass(errors['name.ro'] || errors['name.ru'] || errors.name)"
                />
                <FieldError :msgs="fieldMsg(errors, 'name.ro', 'name.ru', 'name')" />
              </div>
              <div class="space-y-1">
                <Label>Slug *</Label>
                <Input
                  v-model="form.slug"
                  @input="slugManuallyEdited = true"
                  :class="inputClass(errors.slug)"
                />
                <FieldError :msgs="fieldMsg(errors, 'slug')" />
              </div>
              <div class="space-y-1">
                <Label>Код (SKU) * (код должен быть уникальным для каждого товара)</Label>
                <Input v-model="form.code" :class="inputClass(errors.code)" />
                <FieldError :msgs="fieldMsg(errors, 'code')" />
              </div>
              <div class="space-y-1">
                <Label>Бренд *</Label>
                <Select v-model="form.brand_id">
                  <SelectTrigger :class="inputClass(errors.brand_id)">
                    <SelectValue placeholder="Выберите бренд" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Без бренда —</SelectItem>
                    <SelectItem v-for="b in brands" :key="b.id" :value="String(b.id)">{{ b.name }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :msgs="fieldMsg(errors, 'brand_id')" />
              </div>
              <div class="space-y-1">
                <Label>Категория *</Label>
                <Select v-model="form.category_id">
                  <SelectTrigger :class="inputClass(errors.category_id)">
                    <SelectValue placeholder="Выберите категорию" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Без категории —</SelectItem>
                    <SelectItem v-for="cat in categories" :key="cat.id" :value="String(cat.id)">{{ localeStore.t(cat.name) }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :msgs="fieldMsg(errors, 'category_id')" />
              </div>
            </div>

            <div class="space-y-1">
              <Label>Краткое описание * <span class="text-xs text-muted-foreground uppercase">{{ editLocale }}</span></Label>
              <Input
                v-model="form.short_description[editLocale]"
                placeholder="Краткое описание товара (макс. 500 символов)"
                :class="inputClass(errors['short_description.ro'] || errors['short_description.ru'] || errors.short_description)"
              />
              <FieldError :msgs="fieldMsg(errors, 'short_description.ro', 'short_description.ru', 'short_description')" />
            </div>
            <div class="space-y-1">
              <Label>Описание * <span class="text-xs text-muted-foreground uppercase">{{ editLocale }}</span></Label>
              <textarea
                v-model="form.description[editLocale]"
                rows="5"
                :class="['flex w-full rounded-md border bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring',
                  (errors['description.ro'] || errors['description.ru'] || errors.description) ? 'border-destructive focus-visible:ring-destructive' : 'border-input']"
              />
              <FieldError :msgs="fieldMsg(errors, 'description.ro', 'description.ru', 'description')" />
            </div>
          </CardContent>
        </Card>

        <!-- Pricing & Status -->
        <Card>
          <CardHeader><CardTitle>Цена и статус</CardTitle></CardHeader>
          <CardContent class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1">
                <Label>Цена *</Label>
                <Input
                  v-model="form.price"
                  type="number" step="0.01" min="0"
                  :class="inputClass(errors.price)"
                />
                <FieldError :msgs="fieldMsg(errors, 'price')" />
              </div>
              <div class="space-y-1">
                <Label>Старая цена (зачёркнутая)</Label>
                <Input v-model="form.old_price" type="number" step="0.01" min="0" placeholder="Оставьте пустым, если скидки нет" />
                <FieldError :msgs="fieldMsg(errors, 'old_price')" />
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1">
                <Label>Внешний материал *</Label>
                <Select v-model="form.outer_material_id">
                  <SelectTrigger :class="inputClass(errors.outer_material_id)">
                    <SelectValue placeholder="Выберите материал" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Не указано —</SelectItem>
                    <SelectItem v-for="c in classifiers.outer_material" :key="c.id" :value="String(c.id)">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :msgs="fieldMsg(errors, 'outer_material_id')" />
              </div>
              <div class="space-y-1">
                <Label>Подкладка *</Label>
                <Select v-model="form.lining_material_id">
                  <SelectTrigger :class="inputClass(errors.lining_material_id)">
                    <SelectValue placeholder="Выберите подкладку" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Не указано —</SelectItem>
                    <SelectItem v-for="c in classifiers.lining_material" :key="c.id" :value="String(c.id)">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :msgs="fieldMsg(errors, 'lining_material_id')" />
              </div>
              <div class="space-y-1">
                <Label>Наполнитель *</Label>
                <Select v-model="form.filling_id">
                  <SelectTrigger :class="inputClass(errors.filling_id)">
                    <SelectValue placeholder="Выберите наполнитель" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">— Не указано —</SelectItem>
                    <SelectItem v-for="c in classifiers.filling" :key="c.id" :value="String(c.id)">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :msgs="fieldMsg(errors, 'filling_id')" />
              </div>
              <div class="space-y-1">
                <Label>Сезон *</Label>
                <Select v-model="form.season">
                  <SelectTrigger :class="inputClass(errors.season)">
                    <SelectValue placeholder="Выберите сезон" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="none">— Не указано —</SelectItem>
                    <SelectItem v-for="c in seasonOptions" :key="c.key" :value="c.key">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :msgs="fieldMsg(errors, 'season')" />
              </div>
              <div class="space-y-1">
                <Label>Длина *</Label>
                <Select v-model="form.length">
                  <SelectTrigger :class="inputClass(errors.length)">
                    <SelectValue placeholder="Выберите длину" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="none">— Не указано —</SelectItem>
                    <SelectItem v-for="c in lengthOptions" :key="c.key" :value="c.key">{{ localeStore.t(c.name) }}</SelectItem>
                  </SelectContent>
                </Select>
                <FieldError :msgs="fieldMsg(errors, 'length')" />
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
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
                class="grid grid-cols-2 sm:grid-cols-[1fr_90px_120px_100px_140px_36px] gap-2 items-end border rounded-md p-3"
              >
                <div class="space-y-1 col-span-2 sm:col-span-1">
                  <Label class="text-xs text-muted-foreground">Цвет *</Label>
                  <Select :model-value="variant.color_hex" @update:model-value="val => applyColor(variant, val)">
                    <SelectTrigger :class="inputClass(errors[`variants.${i}.color`])">
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
                  <FieldError :msgs="fieldMsg(errors, `variants.${i}.color`)" />
                </div>
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Размер *</Label>
                  <Select v-model="variant.size">
                    <SelectTrigger :class="['w-full', inputClass(errors[`variants.${i}.size`])]">
                      <SelectValue placeholder="Размер" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem v-for="size in SIZES" :key="size" :value="size">{{ size }}</SelectItem>
                    </SelectContent>
                  </Select>
                  <FieldError :msgs="fieldMsg(errors, `variants.${i}.size`)" />
                </div>
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Цена (перекр.)</Label>
                  <Input v-model="variant.price" type="number" step="0.01" min="0" placeholder="—" />
                </div>
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Остаток *</Label>
                  <Input
                    v-model="variant.stock"
                    type="number" min="0" placeholder="0"
                    :class="inputClass(errors[`variants.${i}.stock`])"
                  />
                  <FieldError :msgs="fieldMsg(errors, `variants.${i}.stock`)" />
                </div>
                <div class="space-y-1">
                  <Label class="text-xs text-muted-foreground">Локация</Label>
                  <Select v-model="variant.location_id">
                    <SelectTrigger class="w-full">
                      <SelectValue placeholder="—" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="0">— Не указана —</SelectItem>
                      <SelectItem v-for="loc in locations" :key="loc.id" :value="String(loc.id)">
                        {{ loc.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <Button
                  type="button" variant="ghost" size="sm"
                  class="text-destructive hover:text-destructive px-2 self-end justify-self-end sm:justify-self-auto"
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
import { toast } from 'vue-sonner'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import ProductImagesManager from '@/components/admin/ProductImagesManager.vue'
import { useAdminProductService } from '@/services/adminProductService'
import { useAdminLocationService } from '@/services/adminLocationService'
import { useProductService } from '@/services/productService'
import { useCategoryService } from '@/services/categoryService'
import { useClassifierService } from '@/services/classifierService'
import { useLocaleStore } from '@/store/localeStore'
import { COLORS } from '@/config/colors'
import { useProductValidation } from '@/composables/useProductValidation'

// ─── Constants ────────────────────────────────────────────────────────────────
const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL']

// ─── Props / services ─────────────────────────────────────────────────────────
const props = defineProps({ id: { type: String, default: null } })
const router = useRouter()
const { create, update } = useAdminProductService()
const { getAll: getLocations } = useAdminLocationService()
const { getById } = useProductService()
const { getCategories } = useCategoryService()
const { getClassifiers, getBrands } = useClassifierService()
const localeStore = useLocaleStore()
const { validate, flattenErrors, fieldMsg } = useProductValidation()

// ─── Inline micro-component ───────────────────────────────────────────────────
const FieldError = {
  props: ['msgs'],
  template: `<p v-if="msgs" class="text-xs text-destructive mt-1">{{ Array.isArray(msgs) ? msgs[0] : msgs }}</p>`,
}

// ─── State ────────────────────────────────────────────────────────────────────
const product      = ref(null)
const categories   = ref([])
const brands       = ref([])
const classifiers  = ref({})
const locations    = ref([])
const pageLoading  = ref(false)
const saving       = ref(false)
const errors       = ref({})
const form         = ref(emptyForm())
const slugManuallyEdited = ref(false)
const editLocale   = ref('ro')

// ─── Computed ─────────────────────────────────────────────────────────────────
const seasonOptions = computed(() => classifiers.value?.season ?? [])
const lengthOptions = computed(() => classifiers.value?.length ?? [])

// ─── Helpers ──────────────────────────────────────────────────────────────────
function emptyForm() {
  return {
    name:               { ro: '', ru: '' },
    slug:               '',
    code:               '',
    price:              '',
    old_price:          '',
    category_id:        '0',
    brand_id:           '0',
    description:        { ro: '', ru: '' },
    short_description:  { ro: '', ru: '' },
    is_active:          true,
    is_new:             false,
    is_hit:             false,
    is_sale:            false,
    outer_material_id:  '0',
    lining_material_id: '0',
    filling_id:         '0',
    season:             'none',
    length:             'none',
    hood:               false,
    detachable_hood:    false,
    waterproof:         false,
    variants:            [],
  }
}

function fillFromProduct(p) {
  const loc = v => (typeof v === 'object' && v ? { ro: v.ro ?? '', ru: v.ru ?? '' } : { ro: v ?? '', ru: '' })
  const nid = v => { const n = parseInt(v); return n > 0 ? String(n) : '0' }

  return {
    name:               loc(p.name),
    slug:               p.slug ?? '',
    code:               p.code ?? '',
    price:              String(p.price ?? ''),
    old_price:          p.old_price ? String(p.old_price) : '',
    category_id:        nid(p.category_id),
    brand_id:           nid(p.brand_id),
    description:        loc(p.description),
    short_description:  loc(p.short_description),
    is_active:          !!p.is_active,
    is_new:             !!p.is_new,
    is_hit:             !!p.is_hit,
    is_sale:            !!p.is_sale,
    outer_material_id:  nid(p.outer_material_id),
    lining_material_id: nid(p.lining_material_id),
    filling_id:         nid(p.filling_id),
    season:             p.season  || 'none',
    length:             p.length  || 'none',
    hood:               !!p.hood,
    detachable_hood:    !!p.detachable_hood,
    waterproof:         !!p.waterproof,
    variants: (p.variants ?? []).map(v => ({
      color:       v.color       ?? '',
      color_hex:   v.color_hex   ?? '#000000',
      size:        v.size        ?? '',
      sku:         v.sku         ?? '',
      price:       v.price != null ? String(v.price) : '',
      stock:       v.stock ?? 0,
      location_id: v.location_id ? String(v.location_id) : '0',
    })),
  }
}

function buildPayload(f) {
  const nid = v => parseInt(v) || null

  return {
    ...f,
    category_id:        nid(f.category_id),
    brand_id:           nid(f.brand_id),
    outer_material_id:  nid(f.outer_material_id),
    lining_material_id: nid(f.lining_material_id),
    filling_id:         nid(f.filling_id),
    old_price:          f.old_price || null,
    season:             f.season === 'none' ? null : f.season,
    length:             f.length === 'none' ? null : f.length,
    is_active:          f.is_active      ? 1 : 0,
    is_new:             f.is_new         ? 1 : 0,
    is_hit:             f.is_hit         ? 1 : 0,
    is_sale:            f.is_sale        ? 1 : 0,
    hood:               f.hood           ? 1 : 0,
    detachable_hood:    f.detachable_hood ? 1 : 0,
    waterproof:         f.waterproof     ? 1 : 0,
    variants: f.variants.map(v => ({
      color:       v.color     || null,
      color_hex:   v.color_hex || null,
      size:        v.size      || null,
      sku:         v.sku       || null,
      price:       v.price !== '' ? parseFloat(v.price) : null,
      stock:       parseInt(v.stock) || 0,
      location_id: parseInt(v.location_id) || null,
    })),
  }
}

function inputClass(fieldErrors) {
  return fieldErrors ? 'border-destructive focus-visible:ring-destructive' : ''
}

function slugify(str) {
  return str.toLowerCase().trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
}

function colorLabel(colorJson) {
  try {
    const obj = typeof colorJson === 'string' ? JSON.parse(colorJson) : colorJson
    return localeStore.t(obj)
  } catch {
    return colorJson
  }
}

function showValidationToast(errs) {
  const msgs = flattenErrors(errs)
  toast.error('Исправьте ошибки перед сохранением', {
    description: msgs.slice(0, 5).join('\n') + (msgs.length > 5 ? `\n…ещё ${msgs.length - 5}` : ''),
    duration: 6000,
  })
}

// ─── Form actions ─────────────────────────────────────────────────────────────
function syncSlug() {
  if (!slugManuallyEdited.value) form.value.slug = slugify(form.value.name.ro)
}

function applyColor(variant, hex) {
  const c = COLORS.find(c => c.hex === hex)
  variant.color_hex = hex
  variant.color = c ? JSON.stringify(c.name) : ''
}

function addVariant()     { form.value.variants.push({ color: '', color_hex: '', size: '', sku: '', price: '', stock: 0, location_id: '0' }) }
function removeVariant(i) { form.value.variants.splice(i, 1) }

// ─── Lifecycle ────────────────────────────────────────────────────────────────
onMounted(async () => {
  pageLoading.value = true

  const [cats, cls, brs, locs] = await Promise.all([
    getCategories(),
    getClassifiers(),
    getBrands(),
    getLocations(),
    props.id
      ? getById(props.id).then(p => {
          product.value = p
          slugManuallyEdited.value = true
          form.value = fillFromProduct(p)
        })
      : Promise.resolve(),
  ])

  categories.value  = cats
  classifiers.value = cls
  brands.value      = brs
  locations.value   = locs.filter(l => l.is_active)
  pageLoading.value = false
})

// ─── Submit ───────────────────────────────────────────────────────────────────
async function submit() {
  errors.value = validate(form.value)
  if (Object.keys(errors.value).length) {
    showValidationToast(errors.value)
    return
  }

  saving.value = true
  try {
    const payload = buildPayload(form.value)
    if (props.id) {
      await update(props.id, payload)
    } else {
      await create(payload)
    }
    toast.success('Товар успешно сохранён')
    await router.push('/admin/products')
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors ?? {}
      showValidationToast(errors.value)
    } else {
      toast.error('Ошибка при сохранении товара')
    }
  } finally {
    saving.value = false
  }
}
</script>