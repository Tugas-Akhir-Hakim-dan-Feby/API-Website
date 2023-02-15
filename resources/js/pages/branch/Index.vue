<script>
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import CreateBranch from "./Create.vue";
import EditBranch from "./Edit.vue";

export default {
    data() {
        return {
            id: null,
            msg: "",
            title: "Data Cabang",
            isCreate: false,
            isEdit: false,
            branches: [
                {
                    id: 1,
                    branchName: "Cabang 1",
                    branchAddress: "Jl. Raya No. 1",
                    branchPhone: "081234567890",
                },
                {
                    id: 2,
                    branchName: "Cabang 2",
                    branchAddress: "Jl. Raya No. 2",
                    branchPhone: "081234567890",
                },
                {
                    id: 3,
                    branchName: "Cabang 3",
                    branchAddress: "Jl. Raya No. 3",
                    branchPhone: "081234567890",
                },
            ],
        };
    },
    methods: {
        handleDelete(id) {
            $("#confirmModal").modal("show");
        },
        onCreate() {
            this.title = "Tambah Cabang";
            this.isCreate = true;
        },
        onEdit(id) {
            this.id = id;
            this.title = "Edit Cabang";
            this.isEdit = true;
        },
        onCancel() {
            this.title = "Data Cabang";
            this.isCreate = false;
            this.isEdit = false;
        },
        onDelete() {
            $("#confirmModal").modal("hide");
            $("#successModal").modal("show");
            this.msg = "data berhasil dihapus.";
        },
    },
    components: {
        PageTitle,
        Pagination,
        CreateBranch,
        EditBranch,
        Confirm,
        Success,
    },
};
</script>

<template>
    <PageTitle :title="title" />

    <CreateBranch v-if="isCreate" @onCancel="onCancel($e)" />

    <EditBranch v-else-if="isEdit" :id="id" @onCancel="onCancel($e)" />

    <div class="card" v-else>
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <button class="btn btn-primary mb-2" @click="onCreate">
                        Tambah Cabang
                    </button>
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(branch, index) in branches" :key="index">
                            <th v-html="index + 1"></th>
                            <td v-html="branch.branchName"></td>
                            <td v-html="branch.branchAddress"></td>
                            <td v-html="branch.branchPhone"></td>
                            <td>
                                <button
                                    @click="onEdit(branch.id)"
                                    class="btn btn-warning btn-sm me-2 text-white"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="handleDelete(branch.id)"
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

    <Success :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
