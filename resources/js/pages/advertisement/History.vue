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
            title: "Rekap Iklan",
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
                `recovery=true`,
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
            let isExpired = this.setExpiredAt(expiredAt);
            if (isExpired) {
                return "-";
            }

            dayjs.extend(relativeTime);
            expiredAt = dayjs(expiredAt).locale("id").fromNow();
            return "sisa " + expiredAt + " lagi";
        },
        setExpiredAt(expiredAt) {
            expiredAt = dayjs(expiredAt);

            const currentDate = dayjs();

            if (currentDate.isAfter(expiredAt)) {
                return true;
            }

            return false;
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
        onBack() {
            this.$router.push({ name: "Advertisement" });
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
    <PageTitle :title="title" :isBack="true" @onBack="onBack">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Advertisement' }"
                    >Data Iklan</router-link
                >
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
                <div class="text-center"></div>

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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="advertisements.length < 1">
                            <td colspan="6" class="text-center">
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
                                    v-if="setExpiredAt(advertisement.expiredAt)"
                                    class="btn btn-sm btn__warning btn-status"
                                >
                                    EXPIRED
                                </div>
                                <div
                                    v-else-if="advertisement.isActive == 1"
                                    class="btn btn-sm btn__success btn-status"
                                >
                                    PUBLISH
                                </div>
                                <div
                                    v-else-if="advertisement.isActive == 0"
                                    class="btn btn-sm btn__warning btn-status"
                                >
                                    PENDING
                                </div>
                                <div
                                    v-else-if="advertisement.isActive == 2"
                                    class="btn btn-sm btn__danger btn-status"
                                >
                                    DIHAPUS
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
.btn__danger {
    border-color: #fa5c7c;
    color: #fa5c7c;
    background-color: white;
}
.btn-status {
    cursor: default;
    border-radius: 50px;
}
</style>
