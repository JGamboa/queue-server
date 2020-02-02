import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: import('./views/Login.vue'),
        },
        {
            path: '/404',
            name: '404',
            component: import('./views/404.vue'),
        },
        {
            path: '*',
            redirect: '/404',
        },
    ],
});

export default router;
