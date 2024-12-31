// src/main.js

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

createApp(App)
    .use(router) // Use the router
    .mount('#app'); // Mount it to the element with id="app"
