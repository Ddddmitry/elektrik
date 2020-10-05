<template>
    <div class="backofficeMain">
        <div class="backofficeMain-block">
            <div class="backofficeMain-block-inner">
                <div class="backofficeMain-block__top">
                    <div class="backoffice__title">
                        Мои статьи
                    </div>
                    <router-link href="/backoffice/article_editor/" :to="{name:'article_editor'}" class="button3">
                        Предложить статью
                    </router-link>
                </div>
                <div class="articlesPage-list">
                    <div class="articlesPage-list__single" v-if="!articles.length">
                        Для чего публиковать статьи?<br>
                        Они укрепляют личный бренд исполнителя, позволяют зарекомендовать себя как профессионала и показывают себя с человеческой стороны.
                    </div>
                    <div class="articlesPage-list__single" v-for="article in articles">
                        <div class="articlesPage-list__single-inner">
                            <div class="articlesPage-list__navigation" v-if="article.category || article.themes">
                                <a v-if="!article.active" class="articlesPage-list__type articlesPage-list__type--inactive button2">
                                    Статья неактивна
                                </a>
                                <a v-if="article.category" :href="'/articles/?type=' + article.category.code" class="articlesPage-list__type button2">
                                    {{ article.category.name }}
                                </a>
                                <div class="articlesPage-list-themes" v-if="article.themes">
                                    <div class="articlesPage-list-themes__single" v-for="theme in article.themes">
                                        {{ theme.name }}
                                    </div>
                                </div>
                            </div>
                            <a :href="article.active ? article.link + '?backoffice=y' : false" class="articlesPage-list__title inhLink">
                                {{ article.title }}
                            </a>
                            <div class="articlesPage-list__description" v-if="article.preview_text && article.preview_text != ''">
                                {{ article.preview_text }}
                            </div>
                            <div class="articlesPage-list__img" v-if="article.img && article.img != []">
                                <span class="svg-icon" v-if="article.category.code == 'video'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" viewBox="0 0 69 69">
                                        <path fill="#FFF" fill-rule="nonzero" d="M34.5 69C15.446 69 0 53.554 0 34.5 0 15.446 15.446 0 34.5 0 53.554 0 69 15.446 69 34.5 69 53.554 53.554 69 34.5 69zm14.963-31.952a2.4 2.4 0 0 0 0-4.293L25.938 20.992a2.4 2.4 0 0 0-3.473 2.147v23.524a2.4 2.4 0 0 0 3.473 2.147l23.525-11.762z"/>
                                    </svg>
                                </span>
                                <img :src="article.img.src" :alt="article.title"
                                     class="articlesPage-list__img-tag">
                            </div>
                            <div class="articlesPage-list__info">
                                <div class="articlesPage-helper-data__comment">
                                    <a :href="(article.commentsNumber > 0) ? article.link+'#comments' : false" class="inhLink">
                                        <span class="svg-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path fill="none" fill-rule="evenodd" stroke="#7F7F7F" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17v3l-3.5-3H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h13a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2z"/>
                                            </svg>
                                        </span>
                                        <span v-if="article.commentsNumber > 0">{{article.commentsNumber}}</span>
                                        <span v-else="">нет</span>
                                        <span class="articlesPage-helper-data__comment-text">комментариев</span>
                                    </a>
                                </div>
                                <div class="articlesPage-helper-data__comment">
                                    <a :href="article.link+'#comments'" v-if="article.newCommentsNumber != 0 && article.newCommentsNumber" class="articlesPage-helper-data__comment-new">
                                        {{article.newCommentsNumber}} новых
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    import axios from 'axios';

    export default {
        name: 'articles',
        data: function () {
            return {
                activeArticle: '',
                articles: {},
            }
        },
        methods: {

        },
        mounted () {
            axios.get('/api/backoffice.articles/').then((response) => {
                this.articles = response.data.result;
            });
        },
    }
</script>
