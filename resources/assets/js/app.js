import Vue from 'vue'
import Vuetify from 'vuetify'
import Vuex from 'vuex';
import vuexI18n from 'vuex-i18n'
import store from './store'
import router from './router'
import axios from 'axios'
import $ from 'jquery'
import jwt from 'jsonwebtoken'
import VueTruncate from 'vue-truncate-filter'
import VueSweetalert2 from 'vue-sweetalert2'
import vue2Dropzone from 'vue2-dropzone'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import fontawesome from '@fortawesome/fontawesome'
import brands from '@fortawesome/fontawesome-free-brands'
import solid from '@fortawesome/fontawesome-free-solid'
import regular from '@fortawesome/fontawesome-free-regular'

fontawesome.library.add(brands, solid, regular)

import { translationsPL } from './translationsPL'

import App from './components/App'

Vue.use(Vuetify)
Vue.use(VueTruncate)
Vue.use(vuexI18n.plugin, store)
Vue.use(VueSweetalert2)
Vue.use(vue2Dropzone)

Vue.i18n.add('pl', translationsPL)
Vue.i18n.set('pl')

window.$ = window.jQuery = $
window.axios = axios
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    'X-Requested-With': 'XMLHttpRequest'
}

axios.interceptors.request.use(async config => {
    const token = sessionStorage.getItem('token')
    const getIp = await fetch('api/get-ip')
    const ip = await getIp.text()
    const getPublicKey = await fetch('jwt/public.pem')
    const publicKey = await getPublicKey.text()
    if (token && ip && publicKey) {
        jwt.verify(token, publicKey, (error, decoded) => {
           if (decoded) {
                if (new Date / 1000 | 0 > decoded.exp) {
                    if (ip === decoded.ip) {
                        config.headers['Authorization'] = `Bearer ${token}`
                    }
                    else {
                        console.log('IP mismatch')
                        router.push({ name: 'login' })
                    }
                }
                else {
                    console.log('Token expired')
                    router.push({ name: 'login' })
                }
            }
            else if (error) {
                console.log('Invalid token')
                router.push({ name: 'login' })
           }
        });
    }
    return config;
}, error => {
    return Promise.reject(error)
});

axios.interceptors.response.use(response => {
    return response
}, error => {
    let errorResponseData = error.response.data
    const errors = ["token_invalid", "token_expired", "token_not_provided"]

    if (errorResponseData.error && errors.includes(errorResponseData.error)) {
        sessionStorage.removeItem('token')
        router.push({ name: 'login' })
    }

    return Promise.reject(error)
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store,
    vuexI18n,
    vue2Dropzone
});
