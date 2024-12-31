// src/router/index.js

import { createRouter, createWebHistory } from 'vue-router';
import DashboardLayout from '@/components/DashboardLayout.vue';
import List from '@/components/product/ListProduct.vue';
import AddProduct from '@/components/product/AddProduct.vue';
import ViewProduct from '@/components/product/ViewProduct.vue';
import AddVariation from '@/components/variation/AddVariation.vue';

const routes = [
    {
        path: '/',
        component: DashboardLayout, // Wrap with the layout
        children: [
            {
                path: '',
                name: 'Home',
                component: List,
            },
            {
                path: 'products',
                name: 'List',
                component: List,
            },
            {
                path: 'products/create',
                name: 'AddProduct',
                component: AddProduct,
            },
            {
                path: 'products/:id',
                name: 'ViewProduct',
                component: ViewProduct,
                props: true,
            },
            {
                path: 'variations/create',
                name: 'AddVariation',
                component: AddVariation,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
