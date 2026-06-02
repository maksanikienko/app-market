import axios from 'axios';

export default class CartService {

    static async fetchBasket() {
        const { data } = await axios.get('/api/basket');

        if (data.success && data.order) {
            return data.order.products.map(p => ({
                id:         p.id,
                name:       p.name,
                price:      parseFloat(p.price),
                quantity:   p.pivot?.count      ?? 1,
                image:      p.media_items?.[0]?.thumb_url ?? null,
                variant_id: p.pivot?.variant_id ?? null,
                color:      p.pivot?.color      ?? null,
                color_hex:  p.pivot?.color_hex  ?? null,
                size:       p.pivot?.size        ?? null,
            }));
        }

        return [];
    }

    static async add(productId, variantData = {}) {
        await axios.post(`/api/basket/add/${productId}`, variantData);
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