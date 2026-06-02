import axios from 'axios';

export function useClassifierService() {
    const getClassifiers = () => axios.get('/api/classifiers').then(r => r.data);
    const getBrands = () => axios.get('/api/brands').then(r => r.data);
    return { getClassifiers, getBrands };
}