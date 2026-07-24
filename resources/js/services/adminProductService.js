import axios from 'axios';
import { route } from 'ziggy-js';

export function useAdminProductService() {
    const getAll = (params = {}) => axios.get(route('api.admin.products.index'), { params }).then(r => r.data);

    const create = (data) => axios.post(route('api.admin.products.store'), data).then(r => r.data);

    const update = (id, data) => axios.put(route('api.admin.products.update', id), data).then(r => r.data);

    const remove = (id) => axios.delete(route('api.admin.products.destroy', id)).then(r => r.data);

    const restore     = (id) => axios.post(route('api.admin.products.restore', id)).then(r => r.data);
    const forceDelete = (id) => axios.delete(route('api.admin.products.force-delete', id)).then(r => r.data);

    const uploadImages = (id, files) => {
        const fd = new FormData();
        files.forEach(f => fd.append('images[]', f));
        return axios.post(route('api.admin.products.images.store', id), fd).then(r => r.data);
    };

    const reorderImages = (id, ids) =>
        axios.put(route('api.admin.products.images.reorder', id), { ids }).then(r => r.data);

    const deleteImage = (productId, mediaId) =>
        axios.delete(route('api.admin.products.images.destroy', { product: productId, media: mediaId })).then(r => r.data);

    return { getAll, create, update, remove, restore, forceDelete, uploadImages, reorderImages, deleteImage };
}
