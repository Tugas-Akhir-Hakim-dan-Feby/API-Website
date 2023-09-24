<script>
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import Loader from "../../components/Loader.vue";
import PaginationUtil from "../../store/utils/pagination";
import CreateBranch from "./Create.vue";
import EditBranch from "./Edit.vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/id";

export default {
    data() {
        return {
            uuid: null,
            msg: "",
            title: "Data Iklan",
            isLoading: false,
            isCreate: false,
            isEdit: false,
            banner: null,
            advertisements: [],
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            metaPagination: {},
            errors: {},
        };
    },
    mounted() {
        this.getAdvertisement();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getAdvertisement() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["advertisement", params])
                .then((response) => {
                    this.isLoading = false;
                    this.advertisements = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getExpiredAt(expiredAt) {
            dayjs.extend(relativeTime);
            expiredAt = dayjs(expiredAt).locale("id").fromNow();
            return "sisa " + expiredAt + " lagi";
        },
        handleSubmit() {
            this.isLoading = true;

            let formData = new FormData();
            formData.append("banner", this.banner);

            this.$store
                .dispatch("postDataUpload", ["advertisement", formData])
                .then((response) => {
                    this.isLoading = false;
                    this.getAdvertisement();
                    $("#addAds").modal("hide");
                    window.location.href = response.data.paymentLink;
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        handleDelete(uuid) {
            this.uuid = uuid;
            $("#confirmModal").modal("show");
        },
        onUploadBanner(e) {
            this.banner = e.target.files[0];
        },
        onDelete() {
            this.isLoading = true;
            $("#confirmModal").modal("hide");

            this.$store
                .dispatch("deleteData", ["advertisement", this.uuid])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                    this.msg = "data berhasil dihapus.";
                    this.getAdvertisement();
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        onSearch() {
            setTimeout(() => {
                this.getAdvertisement();
            }, 1000);
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getAdvertisement();
        },
    },
    components: {
        PageTitle,
        Pagination,
        CreateBranch,
        EditBranch,
        Confirm,
        Success,
        Loader,
    },
};
</script>

<template>
    <PageTitle :title="title">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">{{ title }}</li>
        </ol>
    </PageTitle>

    <div class="card position-relative">
        <Loader v-if="isLoading" />

        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <div
                        class="btn-group"
                        role="group"
                        aria-label="Basic example"
                    >
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            v-if="$can('create', 'Advertisement')"
                            data-bs-target="#addAds"
                            data-bs-toggle="modal"
                        >
                            Tambah Iklan
                        </button>
                        <router-link
                            :to="{ name: 'Advertisement History' }"
                            class="btn btn-primary btn-sm"
                        >
                            Rekap Iklan
                        </router-link>
                    </div>
                </div>

                <div
                    class="d-md-flex justify-content-between align-items-center"
                >
                    <div class="me-md-2 me-0"></div>

                    <Pagination
                        :pagination="metaPagination"
                        @onPageChange="onPageChange($event)"
                    />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengirim</th>
                            <th>Email Pengirim</th>
                            <th>Banner</th>
                            <th>Sisa Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="advertisements.length < 1">
                            <td colspan="7" class="text-center">
                                data iklan tidak ada
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(advertisement, index) in advertisements"
                            :key="index"
                        >
                            <th data-label="No" v-html="iteration(index)"></th>
                            <td
                                data-label="Nama Pengirim"
                                v-html="advertisement.user?.name"
                            ></td>
                            <td
                                data-label="Email Pengirim"
                                v-html="advertisement.user?.email"
                            ></td>
                            <td data-label="Banner">
                                <a
                                    :href="advertisement.document?.documentPath"
                                    target="_blank"
                                >
                                    <img
                                        :src="
                                            advertisement.document?.documentPath
                                        "
                                        :alt="
                                            advertisement.document?.documentName
                                        "
                                        height="90"
                                        width="180"
                                    />
                                </a>
                            </td>
                            <td
                                data-label="Sisa Durasi"
                                v-html="getExpiredAt(advertisement.expiredAt)"
                            ></td>
                            <td data-label="Status">
                                <div
                                    v-if="advertisement.isActive == 1"
                                    class="btn btn-sm btn__success btn-status"
                                >
                                    PUBLISH
                                </div>
                                <div
                                    v-if="advertisement.isActive == 0"
                                    class="btn btn-sm btn__warning btn-status"
                                >
                                    PENDING
                                </div>
                            </td>
                            <td data-label="Aksi">
                                <div>
                                    <span>
                                        <button
                                            class="btn btn-danger btn-sm"
                                            @click="
                                                handleDelete(advertisement.uuid)
                                            "
                                            v-if="
                                                $can('delete', 'Advertisement')
                                            "
                                        >
                                            Hapus
                                        </button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="addAds"
        tabindex="-1"
        aria-labelledby="addAdsLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdsLabel">Tambah Iklan</h5>
                </div>
                <form @submit.prevent="handleSubmit" method="post">
                    <div class="modal-body">
                        <label>Masukan Banner (1250 x 410 px)</label>
                        <input
                            type="file"
                            class="form-control"
                            @change="onUploadBanner"
                            :class="{ 'is-invalid': errors.banner }"
                            :disabled="isLoading"
                        />
                        <div
                            class="invalid-feedback"
                            v-if="errors.banner"
                            v-for="(error, index) in errors.banner"
                            :key="index"
                        >
                            {{ error }}.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Kirim
                        </button>
                        <button
                            class="btn btn-primary btn-sm"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Success :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>

<style scoped>
.btn__primary {
    border-color: #727cf5;
    color: #727cf5;
    background-color: white;
}
.btn__success {
    border-color: #0acf97;
    color: #0acf97;
    background-color: white;
}
.btn__warning {
    border-color: #ffbc00;
    color: #ffbc00;
    background-color: white;
}
.btn-status {
    cursor: default;
    border-radius: 50px;
}
</style>
