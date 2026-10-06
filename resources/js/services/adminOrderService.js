import axios from 'axios';
import { route } from 'ziggy-js';

export function useAdminOrderService() {
    const getAll = () => axios.get(route('api.admin.orders.index')).then(r => r.data);
    return { getAll };
}
