import axios from 'axios';

const BASE = '/api/admin/products';

export function useAdminProductService() {
    const getAll = (params = {}) => axios.get(BASE, { params }).then(r => r.data);

    const create = (data) => axios.post(BASE, data).then(r => r.data);

    const update = (id, data) => axios.put(`${BASE}/${id}`, data).then(r => r.data);

    const remove = (id) => axios.delete(`${BASE}/${id}`).then(r => r.data);

    const restore     = (id) => axios.post(`${BASE}/${id}/restore`).then(r => r.data);
    const forceDelete = (id) => axios.delete(`${BASE}/${id}/force-delete`).then(r => r.data);

    const uploadImages = (id, files) => {
        const fd = new FormData();
        files.forEach(f => fd.append('images[]', f));
        return axios.post(`${BASE}/${id}/images`, fd).then(r => r.data);
    };

    const reorderImages = (id, ids) =>
        axios.put(`${BASE}/${id}/images/reorder`, { ids }).then(r => r.data);

    const deleteImage = (productId, mediaId) =>
        axios.delete(`${BASE}/${productId}/images/${mediaId}`).then(r => r.data);

    return { getAll, create, update, remove, restore, forceDelete, uploadImages, reorderImages, deleteImage };
}
