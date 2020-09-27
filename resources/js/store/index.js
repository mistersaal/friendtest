import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        group: 198966118,
        appId: 7555867,
        vkid: '',
        appInit: false,
        isWeb: false,
        preview: false,
        hasTest: false,
        loaded: false,
        mainPage: false,
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
        mainPageOpened(state) {
            state.mainPage = true
        }
    },
    actions: {},
    modules: {}
})
