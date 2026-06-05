import axios from 'axios';

export function useAdminLocationService() {
    const getAll  = ()  => axios.get('/api/admin/locations').then(r => r.data);
    const getStock = () => axios.get('/api/admin/stock').then(r => r.data);
    return { getAll, getStock };
}
