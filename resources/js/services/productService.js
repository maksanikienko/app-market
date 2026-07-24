import axios from 'axios';
import { route } from 'ziggy-js';

function isEmpty(value) {
    if (value == null) return true;
    if (Array.isArray(value)) return value.length === 0;
    if (typeof value === 'string') return value === '';
    return false;
}

function toBoolFlag(value) {
    return value == null ? undefined : (value ? 1 : 0);
}

function buildProductsQuery(params) {
    const raw = {
        page:                  params.page ?? 1,
        per_page:              params.perPage ?? 12,
        search:                params.search,
        'categories[]':        params.categories,
        price_min:             params.priceMin,
        price_max:             params.priceMax,
        'outer_materials[]':   params.outerMaterials,
        'lining_materials[]':  params.liningMaterials,
        'fillings[]':          params.fillings,
        'seasons[]':           params.seasons,
        'lengths[]':           params.lengths,
        hood:                  toBoolFlag(params.hood),
        waterproof:            toBoolFlag(params.waterproof),
        'colors[]':            params.colors,
        'sizes[]':             params.sizes,
    };

    return Object.fromEntries(Object.entries(raw).filter(([, value]) => !isEmpty(value)));
}

export function useProductService() {
    const getProducts = async (params = {}) => {
        const res = await axios.get(route('api.products.index'), { params: buildProductsQuery(params) });
        return res.data;
    };

    const getFeatured = async () => {
        const res = await axios.get(route('api.products.featured'));
        return res.data;
    };

    const getById = async (id) => {
        const res = await axios.get(route('api.products.show', id));
        return res.data;
    };

    const getVariantOptions = async () => {
        const res = await axios.get(route('api.products.variant-options'));
        return res.data;
    };

    return { getProducts, getFeatured, getById, getVariantOptions };
}