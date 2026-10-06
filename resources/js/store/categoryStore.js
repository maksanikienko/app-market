import { defineStore } from 'pinia'
import { useCategoryService } from '@/services/categoryService.js'

const categoryService = useCategoryService()
let pending = null

export const useCategoryStore = defineStore('category', {
    state: () => ({
        categories: [],
        isLoading: false,
    }),

    actions: {
        // Idempotent: concurrent callers share one request, loaded data is reused.
        load() {
            if (this.categories.length) return Promise.resolve()
            if (pending) return pending

            this.isLoading = true
            pending = categoryService.getCategories()
                .then(categories => { this.categories = categories })
                .finally(() => { this.isLoading = false; pending = null })
            return pending
        },
    },
})
