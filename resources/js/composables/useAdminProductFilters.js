import { reactive } from 'vue'

export function useAdminProductFilters() {
    const filters = reactive({
        code:        '',
        name:        '',
        category_id: '',
        is_new:      null,
        is_hit:      null,
        is_sale:     null,
    })

    const reset = () => {
        filters.code        = ''
        filters.name        = ''
        filters.category_id = ''
        filters.is_new      = null
        filters.is_hit      = null
        filters.is_sale     = null
    }

    const toParams = (page = 1) => {
        const p = { page }
        if (filters.code)        p.code        = filters.code
        if (filters.name)        p.name        = filters.name
        if (filters.category_id) p.category_id = filters.category_id
        if (filters.is_new  !== null) p.is_new  = filters.is_new  ? 1 : 0
        if (filters.is_hit  !== null) p.is_hit  = filters.is_hit  ? 1 : 0
        if (filters.is_sale !== null) p.is_sale = filters.is_sale ? 1 : 0
        return p
    }

    return { filters, reset, toParams }
}
