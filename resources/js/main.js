import '../sass/light.scss'
import './bootstrap'
import store from './store'
window.store = store
import router from './router'
import './bootstrapvk'
import Buefy from 'buefy'
import './fontawesome'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

import Vue from 'vue'
Vue.component('vue-fontawesome', FontAwesomeIcon);
Vue.config.productionTip = false
Vue.use(Buefy, {
    defaultIconComponent: 'vue-fontawesome',
    defaultIconPack: 'fas',
})

import App from "./App";

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
