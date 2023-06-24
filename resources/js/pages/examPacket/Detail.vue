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
            examPacket: null,
            errors: {},
            date: null,
            isLoading: false,
            examId: null,
            msg: "",
        };
    },
    beforeMount() {
        if (this.id) {
            this.getExamPacket();
        }
    },
    mounted() {},
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
    <PageTitle
        :title="`Detail Paket ${examPacket.name}`"
        :isBack="true"
        @onBack="onBack($event)"
    >
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Exam Packet' }">
                    <span> Uji Kompetensi </span>
                </router-link>
            </li>
            <li class="breadcrumb-item active">Detail Paket</li>
        </ol>
    </PageTitle>

    <Edit
        v-if="examPacket"
        :examPacket="examPacket"
        @onSuccessEdit="onSuccessEdit"
        :edit="true"
        :isShowParticipant="true"
    />

    <div class="card">
        <div
            class="card-header d-flex justify-content-between align-items-center"
        >
            <h4>Daftar Pertanyaan</h4>
            <div>
                <router-link
                    :to="{
                        name: 'Exam Create',
                        params: { id: id },
                    }"
                    v-if="$can('create', 'Exam')"
                    class="btn btn-sm btn-primary"
                    >Tambah Pertanyaan</router-link
                >
            </div>
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
                                    v-if="$can('update', 'Exam')"
                                    class="btn btn-warning btn-sm me-2 text-white"
                                >
                                    Edit
                                </router-link>
                                <button
                                    class="btn btn-danger btn-sm"
                                    v-if="$can('delete', 'Exam')"
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
