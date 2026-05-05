import { defineStore } from 'pinia';

export const useFilterStore = defineStore('filter', {
  state: () => ({
    selectedCategories: [],
    priceRange: {
      min: null,
      max: null,
    },
  }),

  actions: {
    toggleCategory(categoryId) {
      const index = this.selectedCategories.indexOf(categoryId);
      if (index > -1) {
        this.selectedCategories.splice(index, 1);
      } else {
        this.selectedCategories.push(categoryId);
      }
    },

    setPriceRange(min, max) {
      this.priceRange.min = min ? parseInt(min) : null;
      this.priceRange.max = max ? parseInt(max) : null;
    },

    reset() {
      this.selectedCategories = [];
      this.priceRange = { min: null, max: null };
    },
  },

  getters: {
    hasCategoryFilter: (state) => state.selectedCategories.length > 0,
    hasPriceFilter: (state) => state.priceRange.min !== null || state.priceRange.max !== null,
  },
});
