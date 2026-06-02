import { defineStore } from 'pinia';

export const useFilterStore = defineStore('filter', {
  state: () => ({
    selectedCategories: [],
    priceRange: { min: null, max: null },
    outerMaterials:  [],   // array of IDs
    liningMaterials: [],   // array of IDs
    fillings:        [],   // array of IDs
    seasons:         [],   // array of string keys
    lengths:         [],   // array of string keys
    hood:       null,      // true | null
    waterproof: null,      // true | null
    colors: [],
    sizes:  [],
    mobileFilterOpen: false,
  }),

  actions: {
    toggleCategory(id) {
      const i = this.selectedCategories.indexOf(id);
      i > -1 ? this.selectedCategories.splice(i, 1) : this.selectedCategories.push(id);
    },

    toggleOuter(id) {
      const i = this.outerMaterials.indexOf(id);
      i > -1 ? this.outerMaterials.splice(i, 1) : this.outerMaterials.push(id);
    },
    toggleLining(id) {
      const i = this.liningMaterials.indexOf(id);
      i > -1 ? this.liningMaterials.splice(i, 1) : this.liningMaterials.push(id);
    },
    toggleFilling(id) {
      const i = this.fillings.indexOf(id);
      i > -1 ? this.fillings.splice(i, 1) : this.fillings.push(id);
    },
    toggleSeason(key) {
      const i = this.seasons.indexOf(key);
      i > -1 ? this.seasons.splice(i, 1) : this.seasons.push(key);
    },
    toggleLength(key) {
      const i = this.lengths.indexOf(key);
      i > -1 ? this.lengths.splice(i, 1) : this.lengths.push(key);
    },

    setHood(val)      { this.hood       = this.hood       === val ? null : val; },
    setWaterproof(val){ this.waterproof = this.waterproof === val ? null : val; },
    toggleColor(name) {
      const i = this.colors.indexOf(name);
      i > -1 ? this.colors.splice(i, 1) : this.colors.push(name);
    },
    toggleSize(size) {
      const i = this.sizes.indexOf(size);
      i > -1 ? this.sizes.splice(i, 1) : this.sizes.push(size);
    },
    openMobileFilter()  { this.mobileFilterOpen = true; },
    closeMobileFilter() { this.mobileFilterOpen = false; },

    setPriceRange(min, max) {
      this.priceRange.min = min ? parseFloat(min) : null;
      this.priceRange.max = max ? parseFloat(max) : null;
    },

    reset() {
      this.selectedCategories = [];
      this.priceRange         = { min: null, max: null };
      this.outerMaterials     = [];
      this.liningMaterials    = [];
      this.fillings           = [];
      this.seasons            = [];
      this.lengths            = [];
      this.hood               = null;
      this.waterproof         = null;
      this.colors             = [];
      this.sizes              = [];
    },
  },

  getters: {
    hasAnyFilter: (state) =>
      state.selectedCategories.length > 0 ||
      state.priceRange.min !== null || state.priceRange.max !== null ||
      state.outerMaterials.length > 0 || state.liningMaterials.length > 0 ||
      state.fillings.length > 0 || state.seasons.length > 0 ||
      state.lengths.length > 0 || state.hood !== null || state.waterproof !== null ||
      state.colors.length > 0 || state.sizes.length > 0,

    // kept for backwards compatibility
    hasCategoryFilter: (state) => state.selectedCategories.length > 0,
    hasPriceFilter:    (state) => state.priceRange.min !== null || state.priceRange.max !== null,
  },
});