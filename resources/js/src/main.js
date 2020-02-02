import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/store'
import {apiClient} from "./services/axios.js"
import vuetify from '@/plugins/vuetify'

Vue.prototype.$http = apiClient;
Vue.config.productionTip = false;

import Echo from "laravel-echo"
window.Pusher = require('pusher-js');
export const pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY,
    {
        wsHost: '127.0.0.1',
        wssPort: 6001,
        wsPort: 6001,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        authEndpoint: 'http://127.0.0.1:8000/broadcasting/auth',
        disableStats: true,
        auth: {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }
    });

window.Echo = new Echo({
    broadcaster: 'pusher',
    client: pusher,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.state.auth.user) {
            next({
                name: 'login',
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');
