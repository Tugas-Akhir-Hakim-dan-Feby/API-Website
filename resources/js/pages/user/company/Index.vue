<script>
import Success from "../../../components/notifications/Success.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
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
        getUsers() {
            this.isLoading = true;
            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["user/company-member", params])
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
        handleDelete() {
            $("#confirmModal").modal("show");
        },
        onSearch() {
            setTimeout(() => {
                this.getUsers();
            }, 1000);
        },
        onDelete() {
            $("#confirmModal").modal("hide");
            $("#successModal").modal("show");
            this.msg = "data berhasil dihapus.";
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getUsers();
        },
        onUpdateStatus(id, status) {
            $("#successModal").modal("show");
            this.msg = `status berhasil diperbaharui.`;
        },
    },
    components: { Pagination, PageTitle, Success, Confirm, Loader },
};
</script>
<template>
    <PageTitle :title="'Daftar Pengguna Member Perusahaan'" />

    <div class="card">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="table-responsive">
                <div
                    class="d-md-flex d-block justify-content-between align-items-center mb-2"
                >
                    <div class="text-center">
                        <input
                            type="search"
                            class="form-control"
                            placeholder="pencarian"
                            v-model="filters.search"
                            @input="onSearch"
                        />
                    </div>

                    <div
                        class="d-md-flex justify-content-between align-items-center"
                    >
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
                            <th>Nama Perusahaan</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users" :key="index">
                            <th v-html="index + 1"></th>
                            <td v-html="user.companyMember?.companyName"></td>
                            <td v-html="user.name"></td>
                            <td v-html="user.email"></td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'User Company Show',
                                        params: { id: user.uuid },
                                    }"
                                    class="btn btn-sm btn-info me-2"
                                    >Detail</router-link
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Success :url="{ name: 'User Company' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
