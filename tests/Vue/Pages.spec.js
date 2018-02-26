import { shallow, createLocalVue } from 'vue-test-utils'
import expect from 'expect';
import Login from '../../resources/assets/js/components/Login.vue'
import Pages from '../../resources/assets/js/components/Pages.vue'
import Vuex from 'vuex';
import vuexI18n from 'vuex-i18n'
import VueSweetalert2 from 'vue-sweetalert2'

const localVue = createLocalVue()
localVue.use(Vuex)
const store = new Vuex.Store()
localVue.use(vuexI18n.plugin, store)
localVue.use(VueSweetalert2)

describe('Login', () => {
    beforeEach(() => {
        moxios.install(axios)
    });

    afterEach(() => {
        moxios.uninstall(axios)
    })
})

// describe('Pages', () => {
//     beforeEach(() => {
//         moxios.install(axios)
//     });

//     afterEach(() => {
//         moxios.uninstall(axios)
//     })
// })

it('renders the correct title on login component', () => {
    let wrapper = shallow(Login, { store, localVue })
    expect(wrapper.html()).toContain('Login')
});

// it('renders the correct title on pages component', () => {
//     let wrapper = shallow(Pages, { localVue })
//     expect(wrapper.html()).toContain('Pages')
// });