<script>
import Success from "../../../components/notifications/Success.vue";
import Confirm from "../../../components/notifications/Confirm.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";

export default {
    data() {
        return {
            id: null,
            msg: "",
            isLoading: false,
            isCreate: false,
            isEdit: false,
            users: [],
            msg: "",
        };
    },
    mounted(){
        this.getUsers();
    },
    methods: {
        getUsers(){
            this.$store.dispatch("getData", ["user/hub", {}])
            .then((response) => {
                this.users = response.data;
            })
            .catch((error) => {
                console.log(error);
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
    },
    components: { Pagination, PageTitle, Success, Confirm },
};
</script>
<template>
    <PageTitle :title="'Daftar Pengguna API Pusat'" />
    <CreateSkill v-if="isCreate" @onCancel="onCancel($e)" />
    <EditSkill v-else-if="isEdit" :id="id" @onCancel="onCancel($e)" />

    <div class="card">
        <div class="card-body">
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
                                <!-- <router-link
                                        :to="{
                                            name: 'User Hub Detail',
                                            params: { id: user.id },
                                        }"
                                        class="btn btn-sm btn-info me-2"
                                        >Detail</router-link
                                    > -->
                                <router-link
                                    :to="{
                                        name: 'User Hub Edit',
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

    <Success :url="{ name: 'User Hub' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
