<script>
import Success from "../../../components/notifications/Success.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
import Loader from "../../../components/Loader.vue";
import CreateUser from "./Create.vue"
import EditUser from "./Edit.vue"

export default {
    data() {
        return {
            id : null,
            // title: "Daftar Pengguna API Pusat",
            isCreate: false,
            isEdit: false,
            users: [],
            msg: "",
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: ""
            },
        };
    },
    mounted(){
        this.getUsers();
    },
    methods: {
        getUsers(){
            this.isLoading = true;
            let params = [
                `search=${this.filters.search}`
            ].join("&");

            this.$store
            .dispatch("getData",["user/branch", params])
            .then((response)=>{
                this.isLoading = false;
                this.users = response.data;
            })
            .catch((error) => {
                this.isLoading = false;
                console.log( error);
            })
        },
        handleDelete(id) {
            this.id = id;
            $("#confirmModal").modal("show");
        },
        onCreate() {
            this.title = "Tambah Pengguna";
            this.isCreate = true;
        },
        onEdit(id) {
            this.id = id;
            this.isEdit = true;
        },
        onCancel() {
            this.isCreate = false;
            this.isEdit = false;
            this.getUsers();
        },
        onDelete() {
            this.$store
                .dispatch("deleteData", ["user/branch", this.id])
                .then((response) => {
                    $("#confirmModal").modal("hide");
                    this.showAlert = true;
                    this.msg = "data berhasil dihapus.";
                    this.getUsers();
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        onUpdateStatus(id, status) {
            $("#successModal").modal("show");
            this.msg = `status berhasil diperbaharui.`;
        },
    },
    components: { 
        Pagination,
        PageTitle,  
        Success, 
        Confirm,
        CreateUser,
        EditUser,
        Loader
    },
};
</script>
<template>
    <PageTitle :title="'Daftar Pengguna API Cabang'" />

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <div
                    class="d-md-flex d-block justify-content-between align-items-center mb-2"
                >
                    <div class="text-center">
                        <router-link
                            :to="{ name: 'User Branch Create' }"
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
                            />
                        </div>
                        <!-- <div
                                class="d-flex align-items-center justify-content-center"
                            > -->
                        <!-- <button class="btn no-border me-md-2 me-0">
                                    <i class="mdi mdi-filter-variant"></i>
                                </button> -->
                        <Pagination />
                        <!-- </div> -->
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
                            <th>Cabang</th>
                            <th>Jabatan</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users" :key="index">
                            <th v-html="index + 1"></th>
                            <td v-html="user.name"></td>
                            <td v-html="user.email"></td>
                            <td v-html="user.branch"></td>
                            <td v-html="user.position"></td>
                            <td v-html="user.phone"></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        :checked="user.status"
                                        @click="
                                            onUpdateStatus(user.id, user.status)
                                        "
                                    />
                                </div>
                            </td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'User Branch Edit',
                                        params: { id: user.id },
                                    }"
                                    class="btn btn-sm btn-warning me-2"
                                    >Edit</router-link
                                >
                                <button
                                    @click="handleDelete"
                                    class="btn btn-sm btn-danger"
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

    <Success :url="{ name: 'User Branch' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
