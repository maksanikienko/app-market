import './bootstrap.js';

import axios from 'axios';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { ZiggyVue } from 'ziggy-js';

import App from '@/components/App.vue';
import router from '@/router';

// Seed the XSRF-TOKEN cookie before mounting so every mutating request carries a valid token.
axios.get('/sanctum/csrf-cookie').finally(() => {
    createApp(App)
        .use(createPinia())
        .use(router)
        .use(ZiggyVue)
        .mount('#app');
});
