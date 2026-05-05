<template>
  <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
    <router-link :to="`/product/${product.category.code}/${product.code}`">
      <img :src="imageUrl" class="w-full h-48 object-cover"  alt=""/>
    </router-link>
    <div class="p-4">
      <h3 class="text-lg font-semibold">{{ product.name }}</h3>
      <p class="text-gray-500">{{ product.price }} $</p>
      <button @click="addToCart" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
        Add to cart
      </button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import { useProductService } from './../../services/productService.js';

const props = defineProps({
  product: Object
});

const productService = useProductService();

const imageUrl = computed(() => `/storage/${props.product.image}`);

function addToCart() {
  productService.addToCart(props.product);
}
</script>