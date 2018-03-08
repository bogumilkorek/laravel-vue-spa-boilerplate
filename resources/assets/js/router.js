import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './components/Home'
import Pages from './components/Pages'
import PageShow from './components/PageShow'
import Login from './components/Login'

import store from './store'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'homepage',
            component: PageShow,
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/admin',
            name: 'dashboard',
            component: Pages,
            meta: { requiresAuth: true },
            children: [
                {
                    path: 'pages',
                    name: 'pages',
                    component: Pages,
                    meta: { requiresAuth: true }
                }
            ]
        },
        {
            path: '/:slug',
            name: 'pageShow',
            component: PageShow
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