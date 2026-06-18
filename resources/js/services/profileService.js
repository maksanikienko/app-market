import axios from 'axios';

export function useProfileService() {
    const getOrders = async () => {
        const res = await axios.get('/api/profile/orders');
        return res.data;
    };

    return { getOrders };
}
