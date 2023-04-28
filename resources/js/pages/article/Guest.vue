<script>
import PageTitle from "../../components/PageTitle.vue";
import dayjs from "dayjs";
import "dayjs/locale/id";
import Pagination from "../../components/Pagination.vue";
import Loader from "../../components/Loader.vue";
import NoImages from "../../../images/no-images.png";

export default {
    data() {
        return {
            isLoading: false,
            articles: [],
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            metaPagination: {},
        };
    },
    mounted() {
        this.getArticles();
    },
    methods: {
        getArticles() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["article", params])
                .then((response) => {
                    this.isLoading = false;
                    this.articles = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getDateTime(time) {
            return dayjs(time).locale("id").format("dddd, DD MMMM YYYY");
        },
        defaultImage(e) {
            e.target.src = NoImages;
        },
        onSearch() {
            setTimeout(() => {
                this.getArticles();
            }, 1000);
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getArticles();
        },
    },
    components: { PageTitle, Pagination, Loader },
};
</script>
<template>
    <div class="position-relative">
        <Loader v-if="isLoading" />

        <div class="d-flex justify-content-between flex-md-row flex-column">
            <PageTitle title="Kumpulan Berita" />

            <div class="d-md-flex justify-content-between align-items-center">
                <div class="me-md-2 me-0">
                    <input
                        type="search"
                        class="form-control"
                        placeholder="pencarian"
                        @keyup="onSearch"
                        v-model="filters.search"
                    />
                </div>

                <Pagination
                    :pagination="metaPagination"
                    @onPageChange="onPageChange($event)"
                />
            </div>
        </div>

        <div class="row" id="article">
            <div
                class="col-md-6 col-xxl-3"
                v-for="(article, index) in articles"
                :key="index"
            >
                <div class="card">
                    <div class="card-body">
                        <img
                            :src="
                                article.document
                                    ? article.document.documentPath
                                    : 'NoImages.png'
                            "
                            style="width: 100%; height: 250px"
                            @error="defaultImage"
                        />
                        <ul
                            class="d-flex mt-1 align-items-center align-items-md-start flex-lg-row flex-column"
                            style="list-style-type: none; padding: 0"
                        >
                            <li class="me-3 d-flex align-items-center">
                                <i class="mdi mdi-account me-1"></i>
                                <p class="mb-0" v-html="article.user?.name"></p>
                            </li>
                            <li class="me-3 d-flex align-items-center">
                                <i class="mdi mdi-calendar-clock me-1"></i>
                                <p
                                    class="mb-0"
                                    v-html="getDateTime(article.createdAt)"
                                ></p>
                            </li>
                        </ul>
                        <h4 class="mt-2 my-1">
                            <router-link
                                :to="{
                                    name: 'Article Detail',
                                    params: { slug: article.articleSlug },
                                }"
                                class="text-reset"
                                >{{ article.articleTitle }}</router-link
                            >
                        </h4>
                        <p class="mb-0 text-muted">
                            {{ article.articleExcerpt }}
                        </p>
                        <hr class="bg-dark-lighten my-3" />
                        <h5 class="mt-3 fw-semibold text-muted">
                            <router-link
                                :to="{
                                    name: 'Article Detail',
                                    params: { slug: article.articleSlug },
                                }"
                                >Baca Selengkapnya</router-link
                            >
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <Pagination
            :pagination="metaPagination"
            @onPageChange="onPageChange($event)"
        />
    </div>
</template>
