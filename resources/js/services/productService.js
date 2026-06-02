import axios from 'axios';

export function useProductService() {
    const getProducts = async (params = {}) => {
        const query = {
            page:     params.page    ?? 1,
            per_page: params.perPage ?? 12,
        };

        if (params.search)                  query.search            = params.search;
        if (params.categories?.length)      query['categories[]']   = params.categories;
        if (params.priceMin != null)        query.price_min         = params.priceMin;
        if (params.priceMax != null)        query.price_max         = params.priceMax;
        if (params.outerMaterials?.length)  query['outer_materials[]']  = params.outerMaterials;
        if (params.liningMaterials?.length) query['lining_materials[]'] = params.liningMaterials;
        if (params.fillings?.length)        query['fillings[]']         = params.fillings;
        if (params.seasons?.length)         query['seasons[]']          = params.seasons;
        if (params.lengths?.length)         query['lengths[]']          = params.lengths;
        if (params.hood != null)            query.hood                  = params.hood ? 1 : 0;
        if (params.waterproof != null)      query.waterproof            = params.waterproof ? 1 : 0;
        if (params.colors?.length)          query['colors[]']           = params.colors;
        if (params.sizes?.length)           query['sizes[]']            = params.sizes;

        const res = await axios.get('/api/products', { params: query });
        return res.data;
    };

    const getFeatured = async () => {
        const res = await axios.get('/api/products/featured');
        return res.data;
    };

    const getById = async (id) => {
        const res = await axios.get(`/api/products/${id}`);
        return res.data;
    };

    return { getProducts, getFeatured, getById };
}