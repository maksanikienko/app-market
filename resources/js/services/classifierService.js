import axios from 'axios';
import {route} from "ziggy-js";

export function useClassifierService() {
    const getClassifiers = () => axios.get(route('api.products.classifiers.index')).then(r => r.data);
    const getBrands = () => axios.get(route('api.products.brands.index')).then(r => r.data);
    return { getClassifiers, getBrands };
}