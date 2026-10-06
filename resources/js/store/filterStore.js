import { defineStore } from 'pinia';

const LIST_KEYS = ['selectedCategories', 'outerMaterials', 'liningMaterials', 'fillings', 'seasons', 'lengths', 'colors', 'sizes'];
const FLAG_KEYS = ['hood', 'waterproof'];

const initialFilters = () => ({
    selectedCategories: [],
    priceRange:      { min: null, max: null },
    outerMaterials:  [],   // classifier IDs
    liningMaterials: [],   // classifier IDs
    fillings:        [],   // classifier IDs
    seasons:         [],   // classifier keys
    lengths:         [],   // classifier keys
    colors:          [],
    sizes:           [],
    hood:            null, // true | null
    waterproof:      null, // true | null
});

export const useFilterStore = defineStore('filter', {
    state: initialFilters,

    actions: {
        toggle(key, value) {
            const list = this[key];
            const i = list.indexOf(value);
            i > -1 ? list.splice(i, 1) : list.push(value);
        },

        toggleFlag(key) {
            this[key] = this[key] ? null : true;
        },

        setPriceRange(min, max) {
            this.priceRange.min = min ? parseFloat(min) : null;
            this.priceRange.max = max ? parseFloat(max) : null;
        },

        // Sidebar category navigation: select only this category, or clear it if it is the only one
        selectCategory(id) {
            const isOnly = this.selectedCategories.length === 1 && this.selectedCategories[0] === id;
            this.selectedCategories = isOnly ? [] : [id];
        },

        reset() {
            Object.assign(this, initialFilters());
        },
    },

    getters: {
        activeCount: (state) =>
            LIST_KEYS.reduce((sum, key) => sum + state[key].length, 0) +
            [state.priceRange.min, state.priceRange.max, ...FLAG_KEYS.map(k => state[k])].filter(v => v !== null).length,

        hasAnyFilter() {
            return this.activeCount > 0;
        },

        // Shape expected by productService.getProducts()
        query: (state) => ({
            categories:      state.selectedCategories,
            priceMin:        state.priceRange.min ?? undefined,
            priceMax:        state.priceRange.max ?? undefined,
            outerMaterials:  state.outerMaterials,
            liningMaterials: state.liningMaterials,
            fillings:        state.fillings,
            seasons:         state.seasons,
            lengths:         state.lengths,
            hood:            state.hood,
            waterproof:      state.waterproof,
            colors:          state.colors,
            sizes:           state.sizes,
        }),
    },
});
