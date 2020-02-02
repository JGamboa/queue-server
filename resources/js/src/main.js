import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/store'
import {apiClient} from "./services/axios.js"

Vue.prototype.$http = apiClient

Vue.config.productionTip = false

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
