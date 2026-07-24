import axios from 'axios';
import { route } from 'ziggy-js';

export const adminErrorService = {
    getAll:  (params = {}) => axios.get(route('api.admin.errors.index'), { params }).then(r => r.data),
    remove:  (id)          => axios.delete(route('api.admin.errors.destroy', id)),
};
