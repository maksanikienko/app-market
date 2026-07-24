import axios from 'axios';
import { route } from 'ziggy-js';

export function useAdminLocationService() {
    const getAll  = ()  => axios.get(route('api.admin.locations.index')).then(r => r.data);
    const getStock = () => axios.get(route('api.admin.stock')).then(r => r.data);
    return { getAll, getStock };
}
