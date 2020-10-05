import Vue from 'vue'
import App from './App.vue'
import newsAndStatistics from './newsAndStatistics.vue'
import newsDetail from './newsDetail.vue'
import worksheet from './worksheet.vue'
import reviews from './reviews.vue'
import articles from './articles.vue'
import questionsAndAnswers from './questionsAndAnswers.vue'
import questionsDetail from './questionsDetail.vue'
import editData from './editData.vue'
import editInfo from './editInfo.vue'
import editServices from './editServices.vue'
import editWorks from './editWorks.vue'
import editSertificates from './editSertificates.vue'
import worksheetMain from './worksheetMain.vue'
import articleEditor from './articleEditor.vue'
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
        {path: '/news_and_statistics', component: newsAndStatistics, name: 'news_and_statistics'},
        {path: '/news_and_statistics/:id', component: newsDetail, name: 'news_detail'},
        {path: '/worksheet/:name', component: worksheet,
            children: [{
                path: '/worksheet',
                component: worksheetMain,
                name: 'worksheet',
            },{
                path: '/worksheet/edit_data',
                component: editData,
                name: 'edit_data'
            },{
                path: '/worksheet/edit_info',
                component: editInfo,
                name: 'edit_info'
            },{
                path: '/worksheet/edit_services',
                component: editServices,
                name: 'edit_services'
            },{
                path: '/worksheet/edit_works',
                component: editWorks,
                name: 'edit_works'
            },{
                path: '/worksheet/edit_sertificates',
                component: editSertificates,
                name: 'edit_sertificates'
            },]
        },
        {path: '/reviews', component: reviews, name: 'reviews'},
        {path: '/articles', component: articles, name: 'articles'},
        {path: '/questions_and_answers', component: questionsAndAnswers, name: 'questions_and_answers'},
        {path: '/questions_and_answers/:id', component: questionsDetail, name: 'questions_detail'},
        {path: '/article_editor', component: articleEditor, name: 'article_editor'},
    ],
    linkExactActiveClass: 'active',
    mode: 'history',
    base: baseUrl,
});

let store = new Vuex.Store({
    state: {
        sertificate: false,
        certificationRequest: false,
        raek: false,
        raekRequest: false,
        baseUrl: baseUrl,
    },
    getters: {
        getSertificate: state => {
            return state.sertificate;
        },
        getCertificationRequest: state => {
            return state.certificationRequest;
        },
        getRaek: state => {
          return state.raek;
        },
        getRaekRequest: state => {
            return state.raekRequest;
        },
        getBaseUrl: state => {
            return state.baseUrl;
        },
    },
    mutations: {
        setCertificate(state, payload) {
            state.sertificate = payload;
        },
        setCertificationRequest(state, payload) {
            state.certificationRequest = payload;
        },
        setRaek(state, payload) {
          state.raek = payload;
        },
        setRaekRequest(state, payload) {
            state.raekRequest = payload;
        },
    },
});


new Vue({
    el: '#backoffice',
    router,
    store,
    render: h => h(App)
})
