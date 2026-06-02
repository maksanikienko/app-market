import axios from 'axios';

const BASE = '/api/admin/categories';

export function useAdminCategoryService() {
    const getAll = () => axios.get(BASE).then(r => r.data);
    const getById = (id) => axios.get(`${BASE}/${id}`).then(r => r.data);

    const create = (formData) => axios.post(BASE, formData).then(r => r.data);

    const update = (id, formData) => {
        formData.append('_method', 'PUT');
        return axios.post(`${BASE}/${id}`, formData).then(r => r.data);
    };

    const remove = (id) => axios.delete(`${BASE}/${id}`).then(r => r.data);

    return { getAll, getById, create, update, remove };
}
