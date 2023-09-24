<script>
import Loader from "../../components/Loader.vue";
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import PaginationUtil from "../../store/utils/pagination";

export default {
    data() {
        return {
            msg: "",
            uuid: "",
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
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
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
        handleDelete(uuid) {
            this.uuid = uuid;
            $("#confirmModal").modal("show");
        },
        onDelete() {
            this.isLoading = true;
            this.$store
                .dispatch("deleteData", ["article", this.uuid])
                .then((response) => {
                    this.isLoading = false;
                    this.msg = `data berhasil dihapus.`;
                    this.getArticles();
                    $("#confirmModal").modal("hide");
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (
                        error.response.data.status == "ERROR" &&
                        error.response.data.statusCode == 500
                    ) {
                        this.isError = true;
                        this.msg = error.response.data.message;
                    }
                });
        },
        onUpdateStatus(id, status) {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["article/update-status", id, {}])
                .then((response) => {
                    this.isLoading = false;
                    this.msg = `data berhasil diperbaharui.`;
                    this.getArticles();
                    $("#successModal").modal("show");
                    this.$emit("onCancel", true);
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (
                        error.response.data.status == "ERROR" &&
                        error.response.data.statusCode == 500
                    ) {
                        this.isError = true;
                        this.msg = error.response.data.message;
                    }
                });
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
    components: { PageTitle, Pagination, Success, Confirm, Loader },
};
</script>

<template>
    <PageTitle title="Kumpulan Berita">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Kumpulan Berita</li>
        </ol>
    </PageTitle>

    <div class="card">
        <div class="card-body position-relative">
            <Loader v-if="isLoading" />
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center" v-if="$can('create', 'Article')">
                    <router-link
                        :to="{ name: 'Article Create' }"
                        class="btn btn-primary btn-sm mb-2 me-3"
                    >
                        Tambah Berita
                    </router-link>
                </div>

                <div
                    class="d-md-flex justify-content-between align-items-center"
                >
                    <div class="me-md-2 me-0">
                        <input
                            type="search"
                            class="form-control"
                            placeholder="pencarian"
                            @keyup="onSearch"
                            v-model="filters.search"
                            v-if="$can('search', 'Article')"
                        />
                    </div>

                    <Pagination
                        :pagination="metaPagination"
                        @onPageChange="onPageChange($event)"
                        v-if="$can('pagination', 'Article')"
                    />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th>Pembuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="articles.length < 1">
                            <td colspan="5" class="text-center">
                                data berita tidak ada
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(article, index) in articles"
                            :key="index"
                        >
                            <td v-html="iteration(index)"></td>
                            <td v-html="article.articleTitle"></td>
                            <td v-html="article.user?.name"></td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Article Edit',
                                        params: { id: article.uuid },
                                    }"
                                    v-if="$can('update', 'Article')"
                                    class="btn btn-sm btn-warning text-white me-2"
                                >
                                    Edit
                                </router-link>
                                <button
                                    class="btn btn-sm btn-danger"
                                    @click="handleDelete(article.uuid)"
                                    v-if="$can('delete', 'Article')"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Success :url="{ name: 'Article' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
