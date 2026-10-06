import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '@/store/userStore.js';
import MainLayout from '@/components/layouts/MainLayout.vue';
import AuthLayout from '@/components/layouts/AuthLayout.vue';
import Home from '@/components/pages/Home.vue';

// Header breadcrumbs: i18n key, or { label, to } for a linked crumb
const CATALOG = { label: 'nav.products', to: { name: 'products' } };

const routes = [
    {
        path: '/',
        component: MainLayout,
        children: [
            { path: '',            name: 'home',           component: Home },
            { path: 'products',    name: 'products',       component: () => import('@/components/pages/Products.vue'),      meta: { crumbs: [CATALOG] } },
            { path: 'product/:id', name: 'product-detail', component: () => import('@/components/pages/ProductDetail.vue'), meta: { crumbs: [CATALOG, 'nav.product'] }, props: true },
            { path: 'profile',     name: 'profile',        component: () => import('@/components/pages/Profile.vue'),       meta: { crumbs: ['nav.profile'], auth: true } },
            { path: 'cart',        name: 'cart',           component: () => import('@/components/pages/Cart.vue'),          meta: { crumbs: ['nav.cart'] } },
            { path: 'contact',     name: 'contact',        component: () => import('@/components/pages/Contact.vue'),       meta: { crumbs: ['nav.contact'] } },
        ],
    },
    {
        path: '/admin',
        component: () => import('@/components/layouts/AdminLayout.vue'),
        meta: { auth: true, role: 'admin' },
        children: [
            { path: 'products',            name: 'admin-products',        component: () => import('@/components/pages/admin/AdminProducts.vue') },
            { path: 'products/create',     name: 'admin-product-create',  component: () => import('@/components/pages/admin/AdminProductForm.vue') },
            { path: 'products/:id/edit',   name: 'admin-product-edit',    component: () => import('@/components/pages/admin/AdminProductForm.vue'), props: true },
            { path: 'categories',          name: 'admin-categories',      component: () => import('@/components/pages/admin/AdminCategories.vue') },
            { path: 'categories/create',   name: 'admin-category-create', component: () => import('@/components/pages/admin/AdminCategoryForm.vue') },
            { path: 'categories/:id/edit', name: 'admin-category-edit',   component: () => import('@/components/pages/admin/AdminCategoryForm.vue'), props: true },
            { path: 'orders',              name: 'admin-orders',          component: () => import('@/components/pages/admin/AdminOrders.vue') },
            { path: 'errors',              name: 'admin-errors',          component: () => import('@/components/pages/admin/AdminErrors.vue') },
            { path: 'stock',               name: 'admin-stock',           component: () => import('@/components/pages/admin/AdminStock.vue') },
        ],
    },
    {
        path: '/',
        component: AuthLayout,
        children: [
            { path: 'login',    name: 'login',    component: () => import('@/components/auth/Login.vue') },
            { path: 'register', name: 'register', component: () => import('@/components/auth/Register.vue') },
        ],
    },
    // Must stay last: catches any URL not matched above
    {
        path: '/:pathMatch(.*)*',
        component: MainLayout,
        children: [
            { path: '', name: 'not-found', component: () => import('@/components/pages/NotFound.vue'), meta: { crumbs: ['notFound.crumb'] } },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior: (to, from, saved) => saved ?? { top: 0 },
});

router.beforeEach(async (to) => {
    const store = useUserStore();

    if (!store.user) await store.fetchUser();

    if (to.meta.auth && !store.user) return { name: 'login' };
    if (to.meta.role === 'admin' && !store.isAdmin) return { name: 'home' };
});

export default router;
