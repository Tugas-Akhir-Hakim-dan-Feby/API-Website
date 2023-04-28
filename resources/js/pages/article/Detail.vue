<script>
import PageTitle from "../../components/PageTitle.vue";
import NoImages from "../../../images/no-images.png";
import dayjs from "dayjs";
import "dayjs/locale/id";
import Loader from "../../components/Loader.vue";

export default {
    props: ["slug"],
    data() {
        return {
            isLoading: false,
            article: {},
        };
    },
    mounted() {
        this.getArticle();
    },
    methods: {
        getArticle() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["article/show-by-slug", this.slug])
                .then((response) => {
                    this.isLoading = false;
                    this.article = response.data;
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
        onBack() {
            this.$router.push({ name: "Article" });
        },
    },
    components: { PageTitle, Loader },
};
</script>
<template>
    <div class="position-relative">
        <Loader v-if="isLoading" />

        <PageTitle
            :title="article.articleTitle"
            :isBack="true"
            @onBack="onBack"
        />

        <div class="card">
            <div class="card-body">
                <img
                    :src="
                        article.document
                            ? article.document.documentPath
                            : 'NoImages.png'
                    "
                    style="width: 100%; height: 500px"
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

                <h2 class="mt-2 my-1">
                    {{ article.articleTitle }}
                </h2>

                <p
                    class="mb-0 text-muted mt-3"
                    v-html="article.articleContent"
                ></p>
            </div>
        </div>
    </div>
</template>
