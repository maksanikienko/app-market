<template>
  <Card class="w-full max-w-3xl">
    <CardContent class="pt-6 space-y-4">
      <Label class="text-base font-semibold">Images</Label>

      <!-- Existing images (draggable) -->
      <VueDraggable
        v-if="images.length"
        v-model="images"
        :animation="150"
        handle=".drag-handle"
        class="grid grid-cols-5 gap-3"
        @end="onReorder"
      >
        <div v-for="img in images" :key="img.id" class="relative group">
          <img :src="img.thumb_url" class="aspect-square w-full object-cover rounded-lg" />
          <div class="drag-handle absolute inset-0 cursor-grab active:cursor-grabbing rounded-lg" />
          <button
            type="button"
            @click="removeImage(img)"
            class="absolute top-1 right-1 z-10 bg-black/60 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
          >✕</button>
        </div>
      </VueDraggable>

      <p v-else class="text-sm text-muted-foreground">No images yet.</p>

      <!-- Pending previews -->
      <div v-if="pending.length" class="grid grid-cols-5 gap-3">
        <div v-for="(file, i) in pending" :key="i" class="relative group">
          <img :src="previewUrl(file)" class="aspect-square w-full object-cover rounded-lg opacity-70" />
          <button
            type="button"
            @click="pending.splice(i, 1)"
            class="absolute top-1 right-1 bg-black/60 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
          >✕</button>
        </div>
      </div>

      <!-- Upload controls -->
      <div class="flex items-center gap-3">
        <Input
          type="file"
          multiple
          accept="image/jpeg,image/png,image/webp"
          @change="onFileChange"
          class="flex-1"
        />
        <Button
          v-if="pending.length"
          type="button"
          :disabled="uploading"
          @click="upload"
        >
          {{ uploading ? 'Uploading…' : `Upload (${pending.length})` }}
        </Button>
      </div>
    </CardContent>
  </Card>
</template>

<script setup>
import { ref } from 'vue'
import { VueDraggable } from 'vue-draggable-plus'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useAdminProductService } from '@/services/adminProductService'

const props = defineProps({
  productId: { type: [String, Number], required: true },
  initialImages: { type: Array, default: () => [] },
})

const { uploadImages, reorderImages, deleteImage } = useAdminProductService()

const images = ref([...props.initialImages])
const pending = ref([])
const uploading = ref(false)

const previewUrl = (file) => URL.createObjectURL(file)

const onFileChange = (e) => {
  pending.value.push(...Array.from(e.target.files))
  e.target.value = ''
}

const upload = async () => {
  uploading.value = true
  try {
    images.value = await uploadImages(props.productId, pending.value)
    pending.value = []
  } finally {
    uploading.value = false
  }
}

const removeImage = async (img) => {
  await deleteImage(props.productId, img.id)
  images.value = images.value.filter(i => i.id !== img.id)
}

const onReorder = async () => {
  const ids = images.value.map(i => i.id)
  await reorderImages(props.productId, ids)
}
</script>
