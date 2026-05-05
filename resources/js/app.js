import './bootstrap.js';
import '../css/app.css';

import.meta.glob([
    '../../public/storage/categories/**',
    '../../public/storage/products/**',
    '../../public/storage/**'
]);
import axios from 'axios';
import { createApp } from 'vue';
import { createPinia } from "pinia";

import App from './../js/components/App.vue';
import router from './router';

// Seed the XSRF-TOKEN cookie before the app starts.
// This ensures every POST/PUT/DELETE has a valid CSRF token,
// even after session regeneration (e.g. after login).
axios.get('/sanctum/csrf-cookie').finally(() => {
    const app = createApp(App);
    const pinia = createPinia();

    app.use(pinia);
    app.use(router);
    app.mount('#app');
});