<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import Edit from "./Edit.vue";

export default {
    props: ["id"],
    data() {
        return {
            examPacket: {},
            errors: {},
            date: null,
            isLoading: false,
            examId: null,
            msg: "",
        };
    },
    mounted() {
        this.getExamPacket();
    },
    methods: {
        getExamPacket() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["exam-packet", this.id])
                .then((response) => {
                    this.isLoading = false;
                    this.examPacket = response.data;
                    Object.assign(this.examPacket, {
                        date: response.data.schedule.slice(0, 10),
                    });
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        handleDeleteExam(id) {
            this.examId = id;
            $("#confirmModal").modal("show");
        },
        onDeleteExam() {
            this.$store
                .dispatch("deleteData", ["exam", this.examId])
                .then((response) => {
                    this.isLoading = false;
                    this.msg = "data berhasil dihapus.";
                    this.getExamPacket();
                    $("#confirmModal").modal("hide");
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        onSuccessEdit() {
            this.getExamPacket();
            this.msg = "data berhasil diperbaharui.";
            $("#successModal").modal("show");
        },
        onBack(e) {
            this.$router.push({ name: "Exam Packet" });
        },
    },
    components: { PageTitle, Success, Edit, Confirm, Loader },
};
</script>

<template>
    <PageTitle title="Detail Paket" :isBack="true" @onBack="onBack($event)" />

    <Edit :examPacket="examPacket" @onSuccessEdit="onSuccessEdit" />

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Daftar Pertanyaan</h4>
            <router-link
                :to="{
                    name: 'Exam Create',
                    params: { id: id },
                }"
                class="btn btn-primary"
                >Tambah Pertanyaan</router-link
            >
        </div>
        <div class="card-body position-relative">
            <Loader v-if="isLoading" />

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pertanyaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="examPacket.exams?.length < 1">
                            <td colspan="3" class="text-center">
                                data pertanyaan tidak ada
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(exam, index) in examPacket.exams"
                            :key="index"
                        >
                            <th v-html="index + 1"></th>
                            <td v-html="exam.question"></td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Exam Edit',
                                        params: {
                                            id: examPacket.uuid,
                                            examId: exam.uuid,
                                        },
                                    }"
                                    class="btn btn-warning btn-sm me-2 text-white"
                                >
                                    Edit
                                </router-link>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="handleDeleteExam(exam.uuid)"
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
    <Confirm @onDelete="onDeleteExam" />
</template>
