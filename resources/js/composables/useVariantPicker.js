import { ref, computed, toValue } from 'vue'

const unique = (values) => [...new Set(values.filter(Boolean))]

/**
 * Color/size selection over a product's variants.
 * @param {import('vue').MaybeRefOrGetter<Array>} source — product variants
 */
export function useVariantPicker(source) {
    const selectedColor = ref(null) // hex
    const selectedSize  = ref(null)

    const variants = computed(() => toValue(source) ?? [])

    const colors = computed(() => {
        const byHex = new Map()
        for (const v of variants.value) {
            if (v.color_hex && !byHex.has(v.color_hex)) byHex.set(v.color_hex, { hex: v.color_hex, name: v.color ?? '' })
        }
        return [...byHex.values()]
    })

    const hasColors = computed(() => colors.value.length > 0)
    const hasSizes  = computed(() => variants.value.some(v => v.size))

    const availableSizes = computed(() => {
        const pool = selectedColor.value
            ? variants.value.filter(v => v.color_hex === selectedColor.value)
            : variants.value
        return unique(pool.map(v => v.size))
    })

    const selectedColorName = computed(() => colors.value.find(c => c.hex === selectedColor.value)?.name ?? '')

    const isComplete = computed(() =>
        (!hasColors.value || !!selectedColor.value) && (!hasSizes.value || !!selectedSize.value)
    )

    // Variant matching the current selection; null until every required option is picked
    const variant = computed(() => {
        if (!isComplete.value) return null
        return variants.value.find(v =>
            (!hasColors.value || v.color_hex === selectedColor.value) &&
            (!hasSizes.value  || v.size      === selectedSize.value)
        ) ?? null
    })

    const cartPayload = computed(() => ({
        variant_id: variant.value?.id        ?? null,
        color:      variant.value?.color     ?? null,
        color_hex:  variant.value?.color_hex ?? null,
        size:       variant.value?.size      ?? null,
    }))

    function selectColor(hex) {
        selectedColor.value = hex
        if (selectedSize.value && !availableSizes.value.includes(selectedSize.value)) selectedSize.value = null
    }

    function selectSize(size) {
        selectedSize.value = size
    }

    function reset() {
        selectedColor.value = null
        selectedSize.value  = null
    }

    return {
        selectedColor, selectedSize, selectedColorName,
        colors, hasColors, hasSizes, availableSizes,
        isComplete, variant, cartPayload,
        selectColor, selectSize, reset,
    }
}
