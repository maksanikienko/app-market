// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from './../components/pages/Home.vue';
import Products from './../components/pages/Products.vue';
import { useUserStore } from "../store/userStore.js";
import Login from '../../js/components/auth/Login.vue';
import Register from '../../js/components/auth/Register.vue';
import ProductDetail from "@/components/pages/ProductDetail.vue";
import Profile from "@/components/pages/Profile.vue";
import MainLayout from "@/components/layouts/MainLayout.vue";
import AuthLayout from "@/components/layouts/AuthLayout.vue";
import AdminLayout from "@/components/layouts/AdminLayout.vue";
import AdminProducts from "@/components/pages/admin/AdminProducts.vue";
import AdminCategories from "@/components/pages/admin/AdminCategories.vue";
import AdminOrders from "@/components/pages/admin/AdminOrders.vue";
import Cart from "@/components/pages/Cart.vue";

const routes = [
    {
        path: '/',
        component: MainLayout,
        children: [
            { path: '',         component: Home },
            { path: 'products', component: Products },
            { path: 'product/:id', component: ProductDetail, props: true },
            { path: 'profile', component: Profile, meta: { auth: true } },
            { path: 'cart', component: Cart }
        ]
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { auth: true, role: 'admin' },
        children: [
            { path: 'products',   component: AdminProducts },
            { path: 'categories', component: AdminCategories },
            { path: 'orders',     component: AdminOrders },
        ]
    },
    {
        path: '/',
        component: AuthLayout,
        children: [
            { path: 'login', component: Login },
            { path: 'register', component: Register }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to) => {
    const store = useUserStore()

    if (!store.user) {
        await store.fetchUser()
    }

    // Redirect unauthenticated users
    if (to.meta.auth && !store.user) {
        return '/login'
    }

    // Redirect non-admin users trying to access /admin/*
    if (to.meta.role === 'admin' && !store.isAdmin) {
        return '/'
    }
})
export default router;