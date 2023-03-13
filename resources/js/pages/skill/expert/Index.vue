<script>
import Confirm from "../../../components/notifications/Confirm.vue";
import Success from "../../../components/notifications/Success.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
import PaginationUtil from "../../../store/utils/pagination";
import Loader from "../../../components/Loader.vue";
import CreateSkill from "./Create.vue";
import EditSkill from "./Edit.vue";

export default {
    data() {
        return {
            title: "Daftar Jenis Keahlian Pakar",
            id: null,
            msg: "",
            isLoading: false,
            isCreate: false,
            isEdit: false,
            showAlert: false,
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
        this.getSkills();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getSkills() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["skill/expert", params])
                .then((response) => {
                    this.isLoading = false;
                    this.metaPagination = response.meta;
                    this.skills = response.data;
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
            this.title = "Tambah Keahlian";
            this.isCreate = true;
        },
        onEdit(id) {
            this.id = id;
            this.title = "Edit Keahlian";
            this.isEdit = true;
        },
        onCancel() {
            this.title = "Data Jenis Keahlian Pakar";
            this.isCreate = false;
            this.isEdit = false;
            this.getSkills();
        },
        onDelete() {
            this.$store
                .dispatch("deleteData", ["skill/expert", this.id])
                .then((response) => {
                    $("#confirmModal").modal("hide");
                    this.showAlert = true;
                    this.msg = "data berhasil dihapus.";
                    this.getSkills();
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        onSearch() {
            this.getSkills();
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getSkills();
        },
    },
    components: {
        PageTitle,
        Pagination,
        CreateSkill,
        EditSkill,
        Success,
        Confirm,
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

    <CreateSkill v-if="isCreate" @onCancel="onCancel($e)" />

    <EditSkill v-else-if="isEdit" :id="id" @onCancel="onCancel($e)" />

    <div v-else class="card">
        <div class="card-body position-relative">
            <Loader v-if="isLoading" />
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <button class="btn btn-primary mb-2" @click="onCreate">
                        Tambah Keahlian
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
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Keahlian</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(skill, index) in skills" :key="index">
                            <th v-html="iteration(index)"></th>
                            <td v-html="skill.skillName"></td>
                            <td v-html="skill.skillDescription"></td>
                            <td>
                                <button
                                    @click="onEdit(skill.id)"
                                    class="btn btn-warning text-white btn-sm me-2"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="handleDelete(skill.id)"
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
