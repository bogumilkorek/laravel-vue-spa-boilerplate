import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './components/Home'
import SinglePage from './components/SinglePage'
import NotFound from './components/NotFound'
import Login from './components/admin/Login'
import Pages from './components/admin/Pages'

import store from './store'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'homepage',
            component: SinglePage,
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
            name: 'singlePage',
            component: SinglePage
        },
        {
            path: '*',
            name: 'notFound',
            component: NotFound
        }
    ],
});

router.beforeEach(async (to, from, next) => {
	if (to.meta.requiresAuth) {
		if (store.getters.isLogged && sessionStorage.getItem('token')) {
            return next()
        }
		else {
            return next({ name: 'login' })
        }
	}
	next();
});

export default router