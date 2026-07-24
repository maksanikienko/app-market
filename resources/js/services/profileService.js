import axios from 'axios';
import { route } from 'ziggy-js';

export function useProfileService() {
    const getOrders = async () => {
        const res = await axios.get(route('api.profile.orders'));
        return res.data;
    };

    return { getOrders };
}
