<script>
import Success from "../../../components/notifications/Success.vue";
import Pagination from "../../../components/Pagination.vue";
import PaginationUtil from "../../../store/utils/pagination";
import Loader from "../../../components/Loader.vue";

export default {
    data() {
        return {
            id: "",
            name: "",
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
                `approved=false`,
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
        handleConfirmation() {
            this.$store
                .dispatch("updateData", [
                    "user/expert/confirmation",
                    this.id,
                    {},
                ])
                .then((response) => {
                    $("#confirmModal").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "data berhasil diperbaharui";
                    this.getUsers();
                    this.$emit("onSuccess", true);
                })
                .catch((err) => {});
        },
        confirmation(id, name) {
            this.id = id;
            this.name = name;
            $("#confirmModal").modal("show");
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
    components: { Pagination, Success, Loader },
};
</script>

<template>
    <Loader v-if="isLoading" />

    <div class="table-responsive">
        <div
            class="d-md-flex d-block justify-content-between align-items-center mb-2"
        >
            <div class="text-center">
                <input
                    type="search"
                    class="form-control"
                    placeholder="pencarian"
                    @input="onSearch"
                    v-model="filters.search"
                />
            </div>

            <div class="d-md-flex justify-content-between align-items-center">
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
                    <th>Jenis Keahlian</th>
                    <th>Instansi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users" :key="index">
                    <th v-html="index + 1"></th>
                    <td v-html="user.name"></td>
                    <td v-html="user.welderMember?.welderSkill?.skillName"></td>
                    <td v-html="user.expert?.instance"></td>
                    <td>
                        <a
                            href="#"
                            class="btn btn-sm btn-primary me-2"
                            @click="confirmation(user.uuid, user.name)"
                            >Konfirmasi</a
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Success :msg="msg" />
    <div
        class="modal fade"
        id="confirmModal"
        tabindex="-1"
        aria-labelledby="confirmModalLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">
                        konfirmasi
                    </h5>
                </div>
                <div class="modal-body">
                    <p>
                        apakah anda yakin menjadikan
                        <strong>{{ name }}</strong> sebagai
                        <strong>Pakar</strong>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        class="btn btn-success"
                        @click="handleConfirmation"
                    >
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
