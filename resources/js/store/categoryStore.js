import {defineStore} from "pinia";
import {useCategoryService} from "./../services/categoryService.js";

const categoryService = useCategoryService();
export const useCategoryStore = defineStore('category', {
    state: () => ({
        categories: [] ,
        isLoading: false,
    }),

    actions: {
        async load() {
            this.isLoading = true
            try {
                this.categories = await categoryService.getCategories()
            } finally {
                this.isLoading = false
            }
        },
    },
})