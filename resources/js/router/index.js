import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '@/store/userStore.js';
import MainLayout from '@/components/layouts/MainLayout.vue';
import AuthLayout from '@/components/layouts/AuthLayout.vue';
import Home from '@/components/pages/Home.vue';

const routes = [
    {
        path: '/',
        component: MainLayout,
        children: [
            { path: '',            component: Home, meta: { hideAside: true } },
            { path: 'products',    component: () => import('@/components/pages/Products.vue') },
            { path: 'product/:id', component: () => import('@/components/pages/ProductDetail.vue'), props: true },
            { path: 'profile',     component: () => import('@/components/pages/Profile.vue'), meta: { auth: true } },
            { path: 'cart',        component: () => import('@/components/pages/Cart.vue') },
            { path: 'contact',     component: () => import('@/components/pages/Contact.vue') },
        ],
    },
    {
        path: '/admin',
        component: () => import('@/components/layouts/AdminLayout.vue'),
        meta: { auth: true, role: 'admin' },
        children: [
            { path: 'products',            component: () => import('@/components/pages/admin/AdminProducts.vue') },
            { path: 'products/create',     component: () => import('@/components/pages/admin/AdminProductForm.vue') },
            { path: 'products/:id/edit',   component: () => import('@/components/pages/admin/AdminProductForm.vue'), props: true },
            { path: 'categories',          component: () => import('@/components/pages/admin/AdminCategories.vue') },
            { path: 'categories/create',   component: () => import('@/components/pages/admin/AdminCategoryForm.vue') },
            { path: 'categories/:id/edit', component: () => import('@/components/pages/admin/AdminCategoryForm.vue'), props: true },
            { path: 'orders',              component: () => import('@/components/pages/admin/AdminOrders.vue') },
            { path: 'errors',              component: () => import('@/components/pages/admin/AdminErrors.vue') },
            { path: 'stock',               component: () => import('@/components/pages/admin/AdminStock.vue') },
        ],
    },
    {
        path: '/',
        component: AuthLayout,
        children: [
            { path: 'login',    component: () => import('@/components/auth/Login.vue') },
            { path: 'register', component: () => import('@/components/auth/Register.vue') },
        ],
    },
    // Must stay last: catches any URL not matched above
    {
        path: '/:pathMatch(.*)*',
        component: MainLayout,
        children: [
            { path: '', name: 'not-found', component: () => import('@/components/pages/NotFound.vue'), meta: { hideAside: true } },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    const store = useUserStore();

    if (!store.user) await store.fetchUser();

    if (to.meta.auth && !store.user) return '/login';
    if (to.meta.role === 'admin' && !store.isAdmin) return '/';
});

export default router;
