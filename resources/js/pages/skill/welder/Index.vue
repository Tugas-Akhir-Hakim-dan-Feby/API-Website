<script>
import Confirm from "../../../components/notifications/Confirm.vue";
import Success from "../../../components/notifications/Success.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
import Loader from "../../../components/Loader.vue";
import PaginationUtil from "../../../store/utils/pagination";
import CreateSkill from "./Create.vue";
import EditSkill from "./Edit.vue";

export default {
    data() {
        return {
            title: "Keahlian Welder",
            uuid: null,
            msg: "",
            isCreate: false,
            isEdit: false,
            isLoading: false,

            skills: [],
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
                .dispatch("getData", ["skill/welder", params])
                .then((response) => {
                    this.isLoading = false;
                    this.skills = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        handleDelete(uuid) {
            this.uuid = uuid;
            $("#confirmModal").modal("show");
        },
        onCreate() {
            this.title = "Tambah Keahlian";
            this.isCreate = true;
        },
        onEdit(uuid) {
            this.uuid = uuid;
            this.title = "Edit Keahlian";
            this.isEdit = true;
        },
        onCancel() {
            this.title = "Keahlian Welder";
            this.isCreate = false;
            this.isEdit = false;
            this.getSkills();
        },
        onDelete() {
            this.$store
                .dispatch("deleteData", ["skill/welder", this.uuid])
                .then((response) => {
                    $("#confirmModal").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "data berhasil dihapus.";
                    this.getSkills();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        onSearch() {
            setTimeout(() => {
                this.getSkills();
            }, 1000);
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
    <PageTitle :title="title">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Keahlian Welder</li>
        </ol>
    </PageTitle>

    <CreateSkill v-if="isCreate" @onCancel="onCancel($e)" />

    <EditSkill v-else-if="isEdit" :uuid="uuid" @onCancel="onCancel($e)" />

    <div v-else class="card">
        <div class="card-body">
            <Loader v-if="isLoading" />
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <button
                        class="btn btn-primary mb-2 btn-sm"
                        @click="onCreate"
                        v-if="$can('create', 'Welderskill')"
                    >
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
                            v-if="$can('search', 'Welderskill')"
                        />
                    </div>

                    <Pagination
                        :pagination="metaPagination"
                        @onPageChange="onPageChange($event)"
                        v-if="$can('pagination', 'Welderskill')"
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
                        <tr v-if="skills.length < 1">
                            <td colspan="4" class="text-center">
                                data keahlian welder tidak ada
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(skill, index) in skills"
                            :key="index"
                        >
                            <th v-html="iteration(index)"></th>
                            <td v-html="skill.skillName"></td>
                            <td v-html="skill.skillDescription"></td>
                            <td>
                                <button
                                    @click="onEdit(skill.uuid)"
                                    v-if="$can('update', 'Welderskill')"
                                    class="btn btn-warning text-white btn-sm me-2"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="handleDelete(skill.uuid)"
                                    v-if="$can('delete', 'Welderskill')"
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

    <Success :url="{ name: 'Welder Skill' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
