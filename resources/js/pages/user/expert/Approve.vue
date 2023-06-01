<script>
import Success from "../../../components/notifications/Success.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import Pagination from "../../../components/Pagination.vue";
import PaginationUtil from "../../../store/utils/pagination";
import Loader from "../../../components/Loader.vue";

export default {
    data() {
        return {
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
        handleDelete() {
            $("#confirmModal").modal("show");
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
    },
    components: { Pagination, Success, Confirm, Loader },
};
</script>

<template>
    <Loader v-if="isLoading" />

    <div class="table-responsive">
        <div
            class="d-md-flex d-block justify-content-between align-items-center mb-2"
        >
            <button
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#uploadData"
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
                    <td v-html="user.welderMember?.welderSkill?.skillName"></td>
                    <td v-html="user.expert?.instance"></td>
                    <td>
                        <router-link
                            :to="{
                                name: 'User Expert Show',
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
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan file excel</label>
                            <input type="file" class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <a
                            href="/assets/files/sample-data-pakar.xlsx"
                            target="_blank"
                            >Download Sample</a
                        >
                        <div>
                            <button
                                type="button"
                                class="btn btn-secondary me-2"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Kirim
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Success :url="{ name: 'User Expert' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
