import Vue from 'vue'
import App from './App.vue'
import worksheet from './worksheet.vue'
import editData from './editData.vue'
import worksheetMain from './worksheetMain.vue'
import VueRouter from 'vue-router'

Vue.use(Vuex);

Vue.use(VueRouter);
import Vuex from 'vuex'

let baseUrl = '/backoffice/';
if (typeof webpackHotUpdate == 'function') {
    baseUrl = '/';
}

let router = new VueRouter ({
    routes: [
        {path: '/worksheet/:name', component: worksheet,
            children: [{
                path: '/worksheet',
                component: worksheetMain,
                name: 'worksheet',
            },{
                path: '/worksheet/edit_data',
                component: editData,
                name: 'edit_data'
            },]
        },
    ],
    linkExactActiveClass: 'active',
    mode: 'history',
    base: baseUrl,
});

let store = new Vuex.Store({
    state: {
        sertificate: false,
        raek: false,
        baseUrl: baseUrl,
    },
    getters: {
        getSertificate: state => {
            return state.sertificate;
        },
        getRaek: state => {
          return state.raek;
        },
        getBaseUrl: state => {
            return state.baseUrl;
        },
    },
    mutations: {
        setCertificate(state, payload) {
            state.sertificate = payload;
        },
        setRaek(state, payload) {
          state.raek = payload;
        },
    },
});

new Vue({
    el: '#backoffice',
    router,
    store,
    render: h => h(App),
});
