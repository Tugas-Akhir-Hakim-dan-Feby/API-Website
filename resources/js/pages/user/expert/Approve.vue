<script>
import axios from "axios";
import api from "../../../store/services/api";
import Success from "../../../components/notifications/Success.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import Pagination from "../../../components/Pagination.vue";
import PaginationUtil from "../../../store/utils/pagination";
import Loader from "../../../components/Loader.vue";

export default {
    data() {
        return {
            uuid: null,
            users: [],
            metaPagination: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            msg: "",
            errorMessage: {},
            excelFile: null,
            isDisabled: false,
            isLoading: false,
            uploadProgress: 0,
        };
    },
    computed: {
        dataExcel() {
            let formData = new FormData();

            formData.append("file", this.excelFile);

            return formData;
        },
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getUsers() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
                `approved=true`,
            ].join("&");

            this.$store
                .dispatch("getData", ["user/expert", params])
                .then((response) => {
                    this.isLoading = false;
                    this.users = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        async handleUploadData() {
            this.errorMessage = {};
            this.isDisabled = true;
            try {
                api.init();
                await axios
                    .post("user/expert/upload-excel", this.dataExcel, {
                        onUploadProgress: (progressEvent) => {
                            this.uploadProgress = 100;
                        },
                    })
                    .then((response) => {
                        this.isDisabled = false;
                        $("#uploadData").modal("hide");
                        this.excelFile = null;
                        this.getUsers();
                    });
            } catch (error) {
                this.isDisabled = false;

                if (error.response.data.status_code == 422) {
                    this.errorMessage = error.response.data;
                }

                if (error.response.data.status_code == 500) {
                    $("#uploadData").modal("hide");
                    this.errorMessage = error.response.data;
                }
            } finally {
                this.isDisabled = false;
                this.excelFile = null;
                this.uploadProgress = 0;
            }
        },
        uploadExcelData(e) {
            this.excelFile = e.target.files[0];
        },
        onDelete() {
            $("#confirmModal").modal("hide");
            $("#successModal").modal("show");
            this.msg = "data berhasil dihapus.";
        },
        onUpdateStatus(id, status) {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["user/expert/update-status", id, {}])
                .then((response) => {
                    this.isLoading = false;
                    this.msg = `data berhasil diperbaharui.`;
                    this.getUsers();
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getUsers();
        },
        onSearch() {
            setTimeout(() => {
                this.getUsers();
            }, 1000);
        },
        onDelete() {
            this.isLoading = true;
            $("#confirmModal").modal("hide");

            this.$store
                .dispatch("deleteData", ["user/expert", this.uuid])
                .then((response) => {
                    this.getUsers();
                    this.isLoading = false;
                    this.msg = "data berhasil dihapus.";
                    $("#successModal").modal("show");
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        handleDelete(uuid) {
            this.uuid = uuid;
            this.msg = "apakah anda yakin data ini akan dihapus?";
            $("#confirmModal").modal("show");
        },
    },
    components: { Pagination, Success, Confirm, Loader },
};
</script>

<template>
    <Loader v-if="isLoading" />

    <div
        class="alert alert-danger alert-dismissible text-bg-danger border-0 fade show"
        role="alert"
        v-if="errorMessage.status_code == 500"
    >
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
        {{ errorMessage.message }}
    </div>

    <div class="table-responsive">
        <div
            class="d-md-flex d-block justify-content-between align-items-center mb-2"
        >
            <button
                class="btn btn-primary btn-sm mb-md-0 mb-sm-2"
                data-bs-toggle="modal"
                data-bs-target="#uploadData"
                v-if="$can('upload-excel', 'Expert')"
            >
                Upload Data
            </button>

            <div class="d-md-flex justify-content-between align-items-center">
                <div class="text-center me-2">
                    <input
                        type="search"
                        class="form-control"
                        placeholder="pencarian"
                        @input="onSearch"
                        v-model="filters.search"
                        v-if="$can('search', 'Expert')"
                    />
                </div>

                <Pagination
                    :pagination="metaPagination"
                    @onPageChange="onPageChange($event)"
                    v-if="$can('pagination', 'Expert')"
                />
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pengguna</th>
                    <th>Email Pengguna</th>
                    <th>Jenis Keahlian</th>
                    <th>Instansi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users" :key="index">
                    <th v-html="iteration(index)"></th>
                    <td v-html="user.name"></td>
                    <td v-html="user.email"></td>
                    <td>
                        <ul v-if="user.welderHasSkills.length > 0">
                            <li
                                v-for="(
                                    welderSkill, index
                                ) in user.welderHasSkills"
                                :key="index"
                            >
                                {{ welderSkill.welderSkill.skillName }}
                            </li>
                        </ul>
                        <p v-else>-</p>
                    </td>
                    <td v-html="user.expert?.instance"></td>
                    <td>
                        <router-link
                            :to="{
                                name: 'User Expert Show',
                                params: { id: user.uuid },
                            }"
                            v-if="$can('show', 'Expert')"
                            class="btn btn-sm btn-info me-2"
                            >Detail</router-link
                        >
                        <button
                            class="btn btn-sm btn-danger"
                            @click="handleDelete(user.uuid)"
                        >
                            Hapus
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div
        class="modal fade"
        id="uploadData"
        tabindex="-1"
        aria-labelledby="uploadDataLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadDataLabel">
                        Upload Data
                    </h5>
                </div>
                <form @submit.prevent="handleUploadData" method="post">
                    <div class="modal-body">
                        <div class="mb-0" v-if="uploadProgress < 1">
                            <label>Masukan file excel</label>
                            <input
                                type="file"
                                class="form-control"
                                @change="uploadExcelData"
                                :class="{
                                    'is-invalid': errorMessage?.messages?.file,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage?.messages?.file"
                                v-for="(error, index) in errorMessage?.messages
                                    ?.file"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="progress" v-else>
                            <div
                                class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar"
                                :aria-valuenow="uploadProgress"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                :style="{ width: uploadProgress + '%' }"
                            >
                                Harap Tunggu!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <a
                            href="/assets/files/sample-data-pakar.xlsx"
                            target="_blank"
                            >Unduh Sampel Format</a
                        >
                        <div>
                            <button
                                type="button"
                                class="btn btn-secondary me-2"
                                data-bs-dismiss="modal"
                                :disabled="isDisabled"
                            >
                                Batal
                            </button>
                            <button class="btn btn-primary" v-if="!isDisabled">
                                Kirim
                            </button>
                            <button
                                class="btn btn-primary"
                                type="button"
                                disabled
                                v-if="isDisabled"
                            >
                                <span
                                    class="spinner-border spinner-border-sm me-1"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                Harap Tunggu...
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Success :url="{ name: 'User Expert' }" :msg="msg" />
    <Confirm @onDelete="onDelete" :msg="msg" />
</template>
