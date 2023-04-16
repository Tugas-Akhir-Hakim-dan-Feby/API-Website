<script>
import Success from "../../../components/notifications/Success.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
import PaginationUtil from "../../../store/utils/pagination";
import Util from "../../../store/utils/util";
import Loader from "../../../components/Loader.vue";

export default {
    data() {
        return {
            users: [],
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            metaPagination: {},
            msg: "",
            isLoading: false,
        };
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getGender(gender) {
            return Util.getGender(gender);
        },
        getUsers() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["user/hub", params])
                .then((response) => {
                    this.isLoading = false;
                    this.users = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        onUpdateStatus(id, status) {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["user/hub/update-status", id, {}])
                .then((response) => {
                    this.isLoading = false;
                    this.msg = `data berhasil diperbaharui.`;
                    this.getUsers();
                    $("#successModal").modal("show");
                    this.$emit("onCancel", true);
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        onSearch() {
            setTimeout(() => {
                this.getUsers();
            }, 1000);
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getUsers();
        },
    },
    components: { Pagination, PageTitle, Success, Confirm, Loader },
};
</script>
<template>
    <PageTitle :title="'Daftar Pengguna API Pusat'" />

    <div class="card">
        <div class="card-body position-relative">
            <Loader v-if="isLoading" />
            <div class="table-responsive">
                <div
                    class="d-md-flex d-block justify-content-between align-items-center mb-2"
                >
                    <div class="text-center">
                        <router-link
                            :to="{ name: 'User Hub Create' }"
                            class="btn btn-primary mb-2"
                            >Tambah Pengguna</router-link
                        >
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
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pengguna</th>
                            <th>Email Pengguna</th>
                            <th>Jabatan</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="users.length < 1">
                            <td colspan="8" class="text-center">
                                data pengguna admin pusat tidak ada
                            </td>
                        </tr>
                        <tr v-else v-for="(user, index) in users" :key="index">
                            <th v-html="iteration(index)"></th>
                            <td v-html="user.name"></td>
                            <td v-html="user.email"></td>
                            <td v-html="user.adminHub?.position"></td>
                            <td v-html="getGender(user.adminHub?.gender)"></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        :checked="user.adminHub?.status"
                                        @click="
                                            onUpdateStatus(
                                                user.uuid,
                                                user.adminHub?.status
                                            )
                                        "
                                    />
                                </div>
                            </td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'User Hub Edit',
                                        params: { id: user.uuid },
                                    }"
                                    class="btn btn-sm btn-warning me-2 text-white"
                                    >Edit</router-link
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Success :url="{ name: 'User Hub' }" :msg="msg" />
    <Confirm @onDelete="onDelete" :msg="msg"></Confirm>
</template>
