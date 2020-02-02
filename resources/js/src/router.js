import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: () => import('@/layouts/Main.vue'),
            children: [
                {
                    path: '',
                    name: 'index',
                    component: () => import('./views/Index.vue'),
                    meta: { requiresAuth: true}
                },
                {
                    path: 'jobs',
                    name: 'jobs',
                    component: () => import('./views/Jobs.vue'),
                    meta: { requiresAuth: true}
                },
            ]
            //meta: { requiresAuth: true }
        },
        {
            path: '',
            component: () => import('@/layouts/FullPage.vue'),
            children: [
                // =============================================================================
                // PAGES
                // =============================================================================
                {
                    path: '/login',
                    name: 'login',
                    component: () => import('./views/Login.vue'),
                },
                {
                    path: '/404',
                    name: 'Error-404',
                    component: () => import('./views/Error-404.vue'),
                },
            ]
        },
        {
            path: '*',
            redirect: '/404',
        },
    ],
});

export default router;
