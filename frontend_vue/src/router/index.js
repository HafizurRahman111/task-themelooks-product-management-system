import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/Home.vue';
import Dashboard from '@/views/Dashboard.vue';
import Pos from '@/views/Pos.vue';
import NotFoundView from '@/views/NotFoundView.vue';
import ListProduct from '@/views/product/ListProduct.vue';
import AddProduct from '@/views/product/AddProduct.vue';
import ViewProduct from '@/views/product/ViewProduct.vue';
import AddVariation from '@/views/variation/AddVariation.vue';
import ListVariation from '@/views/variation/ListVariation.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/pos',
        name: 'pos',
        component: Pos,
    },
    {
        path: '/:pathMatch(.*)*', // Catch-all route for 404 pages
        name: 'not-found',
        component: NotFoundView, // 404 page component
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        children: [
            // products 
            {
                path: '/products',
                name: 'ListProduct',
                component: ListProduct,
            },
            {
                path: '/products/create',
                name: 'AddProduct',
                component: AddProduct,
            },
            {
                path: '/products/:id',
                name: 'ViewProduct',
                component: ViewProduct,
                props: true,
            },
            // variations
            {
                path: '/variations',
                name: 'ListVariation',
                component: ListVariation,
            },
            {
                path: '/variations/create',
                name: 'AddVariation',
                component: AddVariation,
            },

        ]
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
