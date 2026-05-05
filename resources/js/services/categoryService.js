import axios from 'axios';

export function useCategoryService() {
    const getCategories = async () => {
        const res = await axios.get('/api/categories');
        return res.data;
    };

    return { getCategories };
}