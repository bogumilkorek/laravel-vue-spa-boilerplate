import Vue from 'vue'
import VueRouter from 'vue-router'

import Pages from './components/Pages'
import Login from './components/Login'

import store from './store'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Pages,
            meta: { requiresAuth: true },
        },
        {
            path: '/pages',
            name: 'pages',
            component: Pages,
            meta: { requiresAuth: true },
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        }
    ],
});

router.beforeEach(async (to, from, next) => {
	if (to.meta.requiresAuth) {
		if (store.getters.isLogged && sessionStorage.getItem('token'))
			return next()
		else
			return next({ name: 'login' })
	}
	next();
});

export default router