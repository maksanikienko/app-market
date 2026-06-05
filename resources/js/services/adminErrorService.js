import axios from 'axios';

const base = '/api/admin/errors';

export const adminErrorService = {
    getAll:  (params = {}) => axios.get(base, { params }).then(r => r.data),
    remove:  (id)          => axios.delete(`${base}/${id}`),
};