<template>
    <div class="articlesPage-main">
        <div class="breadcrumbs breadcrumbs_padding">
            <div class="breadcrumbs-inner typicalBlock typicalBlock_two">
                <div class="breadcrumbs-list">
                    <a href="#" class="breadcrumbs-list__single" @click.prevent="back()">
                        <span class="svg-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path fill="none" fill-rule="nonzero" stroke-width="2" d="M13.314 5.657l-5.657 5.657L2 5.657"/>
                            </svg>
                        </span>
                        <span class="breadcrumbs-list__single-text">Назад</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="htmlArea">
            <div class="backofficeNews-list__single-date">
                {{newsDetail.date}}
            </div>
            <div class="backoffice__mobileTitle">
                {{newsDetail.title}}
            </div>
            <div class="backoffice__title">
                {{newsDetail.title}}
            </div>
            <div v-html="newsDetail.description"></div>
        </div>
        <br>
        <br>
    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        name: 'newsDetail',
        data: function () {
            return {
                newsDetail: {},
            }
        },
        methods: {
            back () {
                this.$router.push({name: 'news_and_statistics'});
            }
        },
        mounted: function () {
            axios.get('/api/backoffice.news.detail/', {params: {id: this.$route.params.id}}).then((response) => {
                this.newsDetail = response.data.result;
            });
        },

    }
</script>
<style scoped>
    .breadcrumbs_padding {
        padding-top: 0px;
        padding-bottom: 30px;
    }
    .breadcrumbs-inner {
        padding-left: 0px;
    }
    .htmlArea {
        border-bottom-width: 0px;
    }
</style>
