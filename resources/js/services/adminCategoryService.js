import axios from 'axios';
import { route } from 'ziggy-js';

export function useAdminCategoryService() {
    const getAll = () => axios.get(route('api.admin.categories.index')).then(r => r.data);
    const getById = (id) => axios.get(route('api.admin.categories.show', id)).then(r => r.data);

    const create = (formData) => axios.post(route('api.admin.categories.store'), formData).then(r => r.data);

    const update = (id, formData) => {
        formData.append('_method', 'PUT');
        return axios.post(route('api.admin.categories.update', id), formData).then(r => r.data);
    };

    const remove = (id) => axios.delete(route('api.admin.categories.destroy', id)).then(r => r.data);

    return { getAll, getById, create, update, remove };
}
