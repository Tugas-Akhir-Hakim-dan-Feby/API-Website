<script>
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import Loader from "../../components/Loader.vue";
import PaginationUtil from "../../store/utils/pagination";
import CreateBranch from "./Create.vue";
import EditBranch from "./Edit.vue";

export default {
    data() {
        return {
            id: null,
            msg: "",
            title: "Data Cabang",
            isLoading: false,
            isCreate: false,
            isEdit: false,
            showAlert: false,
            branches: [],

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
        this.getBranches();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getBranches() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["branch", params])
                .then((response) => {
                    this.isLoading = false;
                    this.branches = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        handleDelete(id) {
            this.id = id;
            $("#confirmModal").modal("show");
        },
        onCreate() {
            this.title = "Tambah Cabang";
            this.isCreate = true;
        },
        onEdit(id) {
            this.id = id;
            this.title = "Edit Cabang";
            this.isEdit = true;
        },
        onCancel() {
            this.title = "Data Cabang";
            this.isCreate = false;
            this.isEdit = false;
            this.getBranches();
        },
        onDelete() {
            this.$store
                .dispatch("deleteData", ["branch", this.id])
                .then((response) => {
                    $("#confirmModal").modal("hide");
                    this.showAlert = true;
                    this.msg = "data berhasil dihapus.";
                    this.getBranches();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        onSearch() {
            setTimeout(() => {
                this.getBranches();
            }, 1000);
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getBranches();
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
    <PageTitle :title="title" />

    <div
        class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
        role="alert"
        v-if="showAlert"
    >
        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
        <strong>berhasil - </strong> {{ msg }}
    </div>

    <CreateBranch v-if="isCreate" @onCancel="onCancel($e)" />

    <EditBranch v-else-if="isEdit" :id="id" @onCancel="onCancel($e)" />

    <div class="card" v-else>
        <div class="card-body position-relative">
            <Loader v-if="isLoading" />
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <button class="btn btn-primary mb-2" @click="onCreate">
                        Tambah Cabang
                    </button>
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
                        />
                    </div>

                    <Pagination
                        :pagination="metaPagination"
                        @onPageChange="onPageChange($event)"
                    />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(branch, index) in branches" :key="index">
                            <th v-html="iteration(index)"></th>
                            <td v-html="branch.branchName"></td>
                            <td v-html="branch.branchAddress"></td>
                            <td v-html="branch.branchPhone"></td>
                            <td>
                                <button
                                    @click="onEdit(branch.id)"
                                    class="btn btn-warning btn-sm me-2 text-white"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="handleDelete(branch.id)"
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

    <Confirm @onDelete="onDelete" />
</template>
