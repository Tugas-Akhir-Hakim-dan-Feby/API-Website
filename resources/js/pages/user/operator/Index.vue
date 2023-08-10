<script>
import Loader from "../../../components/Loader.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import Success from "../../../components/notifications/Success.vue";
import PaginationUtil from "../../../store/utils/pagination";

export default {
    data() {
        return {
            isLoading: false,
            users: [],
            pagination: {
                perPage: 10,
                page: 1,
            },
            metaPagination: {},
            filters: {
                search: "",
            },
        };
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
            ].join("&");

            this.$store
                .dispatch("getData", ["user/operator", params])
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
        handleDelete(uuid) {
            this.uuid = uuid;
            this.msg = "apakah anda yakin data ini akan dihapus?";
            $("#confirmModal").modal("show");
        },
        onDelete() {
            this.isLoading = true;
            $("#confirmModal").modal("hide");

            this.$store
                .dispatch("deleteData", ["user/operator", this.uuid])
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
    components: { PageTitle, Loader, Pagination, Confirm, Success },
};
</script>

<template>
    <PageTitle :title="'Daftar Pengguna TUK Member'">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Daftar Pengguna TUK Member</li>
        </ol>
    </PageTitle>

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
                    <div
                        class="d-md-flex d-block justify-content-between align-items-center mb-2"
                    >
                        <div
                            class="text-center"
                            v-if="$can('create', 'Adminhub')"
                        >
                            <!-- <router-link
                                :to="{ name: 'User Hub Create' }"
                                class="btn btn-primary mb-2 btn-sm"
                                >Tambah Pengguna</router-link
                            > -->
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
                                    v-if="$can('search', 'Adminhub')"
                                />
                            </div>

                            <Pagination
                                :pagination="metaPagination"
                                @onPageChange="onPageChange($event)"
                                v-if="$can('pagination', 'Adminhub')"
                            />
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama TUK</th>
                                <th>Kode TUK</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="users.length < 1">
                                <td colspan="6" class="text-center">
                                    data member tuk tidak ada
                                </td>
                            </tr>
                            <tr
                                v-else
                                v-for="(user, index) in users"
                                :key="index"
                            >
                                <th v-html="iteration(index)"></th>
                                <td v-html="user.operator?.tukName"></td>
                                <td v-html="user.operator?.tukCode"></td>
                                <td v-html="user.email"></td>
                                <td v-html="user.operator?.address"></td>
                                <td>
                                    <router-link
                                        :to="{
                                            name: 'User Operator Show',
                                            params: { id: user.uuid },
                                        }"
                                        v-if="$can('show', 'Weldermember')"
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
            </div>
        </div>
    </div>

    <Success :url="{ name: 'User Operator' }" :msg="msg" />
    <Confirm @onDelete="onDelete" :msg="msg" />
</template>
