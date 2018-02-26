import Vue from 'vue'
import Vuex from 'vuex'
import router from './router'
import Sortable from 'sortablejs'

Vue.use(Vuex)

const types = {
    LOGIN: 'LOGIN',
    LOGOUT: 'LOGOUT'
}

const state = {
    logged: sessionStorage.getItem('token'),
    pageTitle: ''
}

const getters = {
    isLogged: state => state.logged,
    pageTitle: state => state.pageTitle
}

const actions = {
    login({ commit }) {
        commit(types.LOGIN)
        router.push({ path: '/pages' })
    },

    logout({ commit }) {
        axios.post('/api/logout')
            .then((response) => {
                sessionStorage.removeItem('token')
                commit(types.LOGOUT)
                router.push({ path: '/login' })
            })
            .catch((error) => console.log(error))
    },

    setPageTitle: (context, payload) => {
        context.commit("PAGETITLE", payload)
    },
}

const mutations = {
    [types.LOGIN](state) {
        state.logged = 1
    },

    [types.LOGOUT](state) {
        state.logged = 0
    },

    PAGETITLE: (state, payload) => {
        state.pageTitle = payload
    }
}

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
})