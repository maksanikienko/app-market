import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import CartService from './../services/cartService.js';

export const useCartStore = defineStore('cart', () => {
    const items = ref([]);

    const count   = computed(() => items.value.reduce((sum, i) => sum + i.quantity, 0));
    const itemIds = computed(() => new Set(items.value.map(i => i.id)));

    // Load basket from service
    const fetchCart = async () => {
        items.value = await CartService.fetchBasket();
    };

    const add = async (productId) => {
        items.value = await CartService.add(productId);
    };

    const remove = async (productId) => {
        items.value = await CartService.remove(productId);
    };

    const removeAll = async (productId) => {
        items.value = await CartService.removeAll(productId);
    };

    const updateQuantity = async (productId, quantity) => {
        items.value = await CartService.updateQuantity(productId, quantity);
    };

    return { items, count, itemIds, fetchCart, add, remove, removeAll, updateQuantity };
});