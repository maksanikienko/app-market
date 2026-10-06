import { reactive } from 'vue'

const FLAGS = ['is_new', 'is_hit', 'is_sale']

const initialFilters = () => ({
    code:        '',
    name:        '',
    category_id: '',
    is_new:      null,
    is_hit:      null,
    is_sale:     null,
})

export function useAdminProductFilters() {
    const filters = reactive(initialFilters())

    const reset = () => Object.assign(filters, initialFilters())

    // Cycles a flag filter: any → yes → no → any
    const cycleFlag = (key) => {
        filters[key] = filters[key] === null ? true : filters[key] ? false : null
    }

    const toParams = (page = 1) => {
        const params = { page }
        for (const key of ['code', 'name', 'category_id']) {
            if (filters[key]) params[key] = filters[key]
        }
        for (const key of FLAGS) {
            if (filters[key] !== null) params[key] = filters[key] ? 1 : 0
        }
        return params
    }

    return { filters, reset, cycleFlag, toParams }
}
