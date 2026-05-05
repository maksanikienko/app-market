import axios from 'axios';

export function useProductService() {
    /**
     * Fetch paginated products.
     * @param {Object} params - { page, perPage, search, categories, priceMin, priceMax }
     * @returns {Promise<{ data: Product[], meta: PaginationMeta }>}
     */
    const getProducts = async (params = {}) => {
        const query = {
            page: params.page ?? 1,
            per_page: params.perPage ?? 12,
        };

        if (params.search)           query.search    = params.search;
        if (params.categories?.length) query['categories[]'] = params.categories;
        if (params.priceMin != null) query.price_min = params.priceMin;
        if (params.priceMax != null) query.price_max = params.priceMax;

        const res = await axios.get('/api/products', { params: query });
        return res.data; // { data, meta }
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