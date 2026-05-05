import axios from 'axios';

export default class CartService {

    static async fetchBasket() {
        const { data } = await axios.get('/api/basket');

        if (data.success && data.order) {
            return data.order.products.map(p => ({
                id: p.id,
                name: p.name,
                price: p.price,
                quantity: p.pivot?.count ?? 1,
                image: p.image ? `/storage/${p.image}` : null
            }));
        }

        return [];
    }

    static async add(productId) {
        await axios.post(`/api/basket/add/${productId}`);
        return CartService.fetchBasket();
    }

    static async remove(productId) {
        await axios.post(`/api/basket/remove/${productId}`);
        return CartService.fetchBasket();
    }

    static async updateQuantity(productId, quantity) {
        await axios.post(`/api/basket/update`, {
            product_id: productId,
            quantity: quantity
        });
        return CartService.fetchBasket();
    }

    static async placeOrder({ name = '', phone = '' } = {}) {
        const { data } = await axios.post('/api/basket/place', { name, phone });
        return data; // { success, message, order_id }
    }
}