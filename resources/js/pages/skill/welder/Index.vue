<script>
import Confirm from "../../../components/notifications/Confirm.vue";
import Success from "../../../components/notifications/Success.vue";
import PageTitle from "../../../components/PageTitle.vue";
import Pagination from "../../../components/Pagination.vue";
import CreateSkill from "./Create.vue";
import EditSkill from "./Edit.vue";

export default {
    data() {
        return {
            title: "Daftar Jenis Keahlian Welder",
            id: null,
            msg: "",
            isCreate: false,
            isEdit: false,
            skills: [
                {
                    skillName: "Keahlian 1",
                    skillDescription: "Deskripsi keahlian 1",
                },
                {
                    skillName: "Keahlian 2",
                    skillDescription: "Deskripsi keahlian 2",
                },
                {
                    skillName: "Keahlian 3",
                    skillDescription: "Deskripsi keahlian 3",
                },
            ],
        };
    },
    methods: {
        handleDelete(id) {
            $("#confirmModal").modal("show");
        },
        onCreate() {
            this.title = "Tambah Keahlian";
            this.isCreate = true;
        },
        onEdit(id) {
            this.id = id;
            this.title = "Edit Keahlian";
            this.isEdit = true;
        },
        onCancel() {
            this.title = "Data Jenis Keahlian Welder";
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
        CreateSkill,
        EditSkill,
        Success,
        Confirm,
    },
};
</script>
<template>
    <PageTitle :title="title" />

    <CreateSkill v-if="isCreate" @onCancel="onCancel($e)" />

    <EditSkill v-else-if="isEdit" :id="id" @onCancel="onCancel($e)" />

    <div v-else class="card">
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <button class="btn btn-primary mb-2" @click="onCreate">
                        Tambah Keahlian
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
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Keahlian</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(skill, index) in skills" :key="index">
                            <th v-html="index + 1"></th>
                            <td v-html="skill.skillName"></td>
                            <td v-html="skill.skillDescription"></td>
                            <td>
                                <button
                                    @click="onEdit(skill.id)"
                                    class="btn btn-warning btn-sm me-2"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="handleDelete(skill.id)"
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

    <Success :url="{ name: 'Welder Skill' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
