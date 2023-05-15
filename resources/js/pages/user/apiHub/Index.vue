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
            .dispatch("getData",["user/hub", params])
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
            // console.log("OK");
            this.isCreate = false;
            this.isEdit = false;
            this.getUsers();
        },
        onDelete() {
            this.$store
                .dispatch("deleteData", ["user/hub", this.id])
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
        onSearch() {
            this.getUsers();
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getUsers();
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
    <PageTitle :title="title" />

    <CreateUser v-if="isCreate" @onCancel="onCancel($e)" />

    <EditUser v-else-if="isEdit" :id="id" @onCancel="onCancel($e)" />

    <div v-else class="card">
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

                        <Pagination />
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
                            <th>Alamat</th>
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
                            <td v-html="user.adminHub?.address"></td>
                            <td v-html="user.adminHub?.position"></td>
                            <td v-html="user.adminHub?.phone"></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        :checked="user.adminHub?.status"
                                        @click="
                                            onUpdateStatus(user.id, user.adminHub?.status)
                                        "
                                    />
                                </div>
                            </td>
                            <td>
                                <!-- <router-link
                                        :to="{
                                            name: 'User Hub Detail',
                                            params: { id: user.id },
                                        }"
                                        class="btn btn-sm btn-info me-2"
                                        >Detail</router-link
                                    > -->
                                <!-- <router-link
                                    :to="{
                                        name: 'User Hub Edit',
                                        params: { id: user.id },
                                    }"
                                    class="btn btn-sm btn-warning me-2"
                                    >Edit</router-link
                                > -->
                                <button
                                    @click="onEdit(user.id)"
                                    class="btn btn-warning text-white btn-sm me-2"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="handleDelete(user.id)"
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

    <Success :url="{ name: 'User Hub' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
