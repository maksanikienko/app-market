import axios from 'axios';
import {route} from "ziggy-js";

export function useCategoryService() {
    const getCategories = async () => {
        const res = await axios.get(route('api.products.categories'));
        return res.data;
    };

    return { getCategories };
}