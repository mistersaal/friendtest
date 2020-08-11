import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        group: 197729801,
        appId: 7539827,
        vkid: '',
        appInit: false,
        isWeb: false,
        preview: false,
        hasTest: false,
        loaded: false,
    },
    mutations: {
        init(state) {
            state.appInit = true
        },
        web(state) {
            state.isWeb = true
        },
        preview(state) {
            state.preview = true
        },
        setTest(state, value) {
            state.hasTest = value
        },
        setVkid(state, vkid) {
            state.vkid = vkid
        },
        loaded(state) {
            state.loaded = true
        },
    },
    actions: {},
    modules: {}
})