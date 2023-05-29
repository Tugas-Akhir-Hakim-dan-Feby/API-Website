<script>
import Success from "../../../components/notifications/Success.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
import PaginationUtil from "../../../store/utils/pagination";
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
        getUsers() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["user/welder-member", params])
                .then((response) => {
                    this.isLoading = false;
                    this.users = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        handleDelete() {
            $("#confirmModal").modal("show");
        },
        onDelete() {
            $("#confirmModal").modal("hide");
            $("#successModal").modal("show");
            this.msg = "data berhasil dihapus.";
        },
        onUpdateStatus(id, status) {
            $("#successModal").modal("show");
            this.msg = `status berhasil diperbaharui.`;
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
    <PageTitle :title="'Daftar Pengguna Welder Member'" />

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
                            <th>No. KTP</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Keahlian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users" :key="index">
                            <th v-html="iteration(index)"></th>
                            <td v-html="user.welderMember?.residentIdCard"></td>
                            <td v-html="user.name"></td>
                            <td v-html="user.email"></td>
                            <td
                                v-html="
                                    user.welderMember?.welderSkill?.skillName
                                "
                            ></td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'User Member Show',
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
