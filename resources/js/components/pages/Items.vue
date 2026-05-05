<!--<template>-->
<!--  <div>-->
<!--    <h1 class="text-2xl font-bold mb-4">All Items</h1>-->
<!--    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">-->
<!--      <Card v-for="product in products" :key="product.id" :product="product" @add-to-cart="addToCart" />-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<script setup>-->
<!--import { ref, onMounted } from 'vue';-->
<!--import Card from '../../components/parts/Card.vue';-->
<!--import { useProductService } from '../../services/productService.js';-->
<!--import { useCartStore } from '../../store/cartStore';-->

<!--const products = ref([]);-->
<!--const productService = useProductService();-->
<!--const cartStore = useCartStore();-->

<!--async function loadProducts() {-->
<!--  products.value = await productService.getAll();-->
<!--  console.log(products.value)-->
<!--}-->

<!--function addToCart(product) {-->
<!--  cartStore.add(product);-->
<!--}-->

<!--onMounted(() => {-->
<!--  loadProducts();-->
<!--});-->
<!--</script>-->