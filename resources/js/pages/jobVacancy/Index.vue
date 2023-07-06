<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import Success from "../../components/notifications/Success.vue";
import Confirm from "../../components/notifications/Confirm.vue";
import PaginationUtil from "../../store/utils/pagination";
import Util from "../../store/utils/util";

import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    data() {
        return {
            uuid: "",
            jobVacancies: null,
            roles: null,
            metaPagination: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            isLoading: false,
        };
    },
    mounted() {
        this.getUser();
        this.getJobVacancies();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.roles = response.roles;
                })
                .catch((error) => {});
        },
        getJobVacancies() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["job-vacancy", params])
                .then((response) => {
                    this.isLoading = false;
                    this.jobVacancies = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getDeadline(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        getRupiah(amount) {
            return Util.getRupiah(amount);
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getJobVacancies();
        },
        onDelete() {
            this.isLoading = true;
            $("#confirmModal").modal("hide");

            this.$store
                .dispatch("deleteData", ["job-vacancy", this.uuid])
                .then((response) => {
                    this.getJobVacancies();
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
        checkRoleAdminApp() {
            if (
                this.roles &&
                this.roles.includes(this.$store.state.ADMIN_APP)
            ) {
                return true;
            }
            return false;
        },
    },
    components: { PageTitle, Pagination, Loader, Success, Confirm },
};
</script>

<template>
    <PageTitle title="Lowongan Pekerjaan">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Lowongan Pekerjaan</li>
        </ol>
    </PageTitle>

    <div
        class="alert alert-warning"
        v-if="jobVacancies && !checkRoleAdminApp()"
    >
        Limit postingan anda tersisa
        <strong>{{ 4 - jobVacancies.length }}</strong
        >.
    </div>

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <router-link
                        :to="{ name: 'Job Vacancy Create' }"
                        class="btn btn-sm btn-primary mb-2"
                        v-if="jobVacancies && jobVacancies.length < 4"
                    >
                        Tambah Lowongan
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
                            <th v-if="checkRoleAdminApp()">Nama Perusahaan</th>
                            <th>Jenis Lowongan</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Penempatan</th>
                            <th>Perkiraan Gaji</th>
                            <th>Waktu Penutupan</th>
                            <th>Jumlah Pendaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(jobVancacy, index) in jobVacancies"
                            :key="index"
                        >
                            <th v-html="iteration(index)"></th>
                            <td
                                v-if="checkRoleAdminApp()"
                                v-html="jobVancacy.companyMember?.companyName"
                            ></td>
                            <td v-html="jobVancacy.welderSkill?.skillName"></td>
                            <td v-html="jobVancacy.workType"></td>
                            <td v-html="jobVancacy.placement"></td>
                            <td v-html="getRupiah(jobVancacy.salary)"></td>
                            <td v-html="getDeadline(jobVancacy.deadline)"></td>
                            <td>
                                <span class="badge btn-success"
                                    >1 Pendaftar</span
                                >
                            </td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Job Vacancy Edit',
                                        params: { id: jobVancacy.uuid },
                                    }"
                                    class="btn btn-sm btn-warning text-white"
                                    >Edit</router-link
                                >
                                <button
                                    @click="handleDelete(jobVancacy.uuid)"
                                    class="btn btn-sm btn-danger text-white"
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

    <Success :url="{ name: 'Job Vacancy' }" :msg="msg" />
    <Confirm @onDelete="onDelete" :msg="msg"></Confirm>
</template>
