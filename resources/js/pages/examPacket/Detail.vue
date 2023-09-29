<script>
import Loader from "../../components/Loader.vue";
import Pagination from "../../components/Pagination.vue";
import PageTitle from "../../components/PageTitle.vue";
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import Show from "./Show.vue";

export default {
    props: ["id"],
    data() {
        return {
            examPacket: null,
            exams: [],
            pagination: {
                page: 1,
                perPage: 10,
            },
            metaPagination: {},
            errors: {},
            date: null,
            isLoading: false,
            isLoadingExam: false,
            examId: null,
            msg: "",
        };
    },
    beforeMount() {
        this.getExam();
        this.getExamPacket();
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
                        examSchedule: this.formatDate(
                            response.data.examSchedule
                        ),
                    });
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getExam() {
            this.isLoadingExam = true;

            const params = [
                `exam_packet_id=${this.id}`,
                `page=${this.pagination.page}`,
                `per_page=${this.pagination.perPage}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["exam", params])
                .then((response) => {
                    this.isLoadingExam = false;
                    this.exams = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((err) => {
                    this.isLoadingExam = false;
                });
        },
        handleDeleteExam(id) {
            this.examId = id;
            $("#confirmModal").modal("show");
        },
        onDeleteExam() {
            $("#confirmModal").modal("hide");
            this.$store
                .dispatch("deleteData", ["exam", this.examId])
                .then((response) => {
                    this.isLoading = false;
                    this.msg = "data berhasil dihapus.";
                    this.getExamPacket();
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
        onPageChange(e) {
            this.pagination.page = e;
            this.getExam();
        },
        formatDate(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
    },
    components: { PageTitle, Success, Show, Confirm, Loader, Pagination },
};
</script>

<template>
    <div class="alert alert-warning mt-3 text-center" v-if="!examPacket">
        Harap tunggu...
    </div>

    <PageTitle
        v-if="examPacket"
        :title="`Detail Ujian ${examPacket.competenceSchema?.skillName}`"
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
            <li class="breadcrumb-item active">Detail Ujian</li>
        </ol>
    </PageTitle>

    <Show
        v-if="examPacket"
        :examPacket="examPacket"
        @onSuccessEdit="onSuccessEdit"
        :edit="true"
        :isShowParticipant="true"
    />

    <div class="card" v-if="examPacket">
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
            <Loader v-if="isLoadingExam" />

            <div class="d-flex justify-content-end">
                <Pagination
                    :pagination="metaPagination"
                    @onPageChange="onPageChange($event)"
                />
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Pertanyaan</th>
                            <th>Jenis Pertanyaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="exams.length < 1">
                            <td colspan="3" class="text-center">
                                data pertanyaan tidak ada
                            </td>
                        </tr>
                        <tr v-else v-for="(exam, index) in exams" :key="index">
                            <td v-html="exam.question"></td>
                            <td
                                v-html="
                                    exam.answerType ==
                                    $store.state.QUESTION_TYPE.TRUE_FALSE
                                        ? 'Benar/Salah'
                                        : 'Pilihan Ganda'
                                "
                            ></td>
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
