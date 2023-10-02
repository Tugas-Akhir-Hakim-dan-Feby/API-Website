<script>
import axios from "axios";
import api from "../../store/services/api";
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
            message: "",
            excelFile: null,
            isDisabled: false,
            uploadProgress: 0,
            errorMessage: {},
        };
    },
    computed: {
        dataExcel() {
            let formData = new FormData();

            formData.append("file", this.excelFile);
            formData.append("exam_packet_id", this.id);

            return formData;
        },
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
        async handleUploadData() {
            this.errorMessage = {};
            this.isDisabled = true;
            try {
                api.init();
                await axios
                    .post("exam/import", this.dataExcel, {
                        onUploadProgress: (progressEvent) => {
                            this.uploadProgress = 100;
                        },
                    })
                    .then((response) => {
                        this.isDisabled = false;
                        $("#uploadData").modal("hide");
                        this.excelFile = null;
                        this.getExam();
                    });
            } catch (error) {
                this.isDisabled = false;
                console.log(error);
                if (error.response.data.status_code == 422) {
                    this.errorMessage = error.response.data;
                }

                if (error.response.data.status_code == 500) {
                    $("#uploadData").modal("hide");
                    this.errorMessage = error.response.data;
                }
            } finally {
                this.isDisabled = false;
                this.excelFile = null;
                this.uploadProgress = 0;
            }
        },
        uploadExcelData(e) {
            this.excelFile = e.target.files[0];
        },
        handleDeleteExam(id) {
            this.examId = id;
            $("#confirmModal").modal("show");
        },
        onDeleteExam() {
            $("#confirmModal").modal("hide");
            this.message = "";
            this.$store
                .dispatch("deleteData", ["exam", this.examId])
                .then((response) => {
                    this.isLoading = false;
                    this.message = "data berhasil dihapus.";
                    this.getExam();
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        onSuccessEdit() {
            this.getExamPacket();
            this.message = "data berhasil diperbaharui.";
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

            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <button
                    class="btn btn-primary btn-sm mb-md-0 mb-sm-2"
                    data-bs-toggle="modal"
                    data-bs-target="#uploadData"
                >
                    Unggah Pertanyaan
                </button>

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
                                            examId: index,
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
                                    @click="handleDeleteExam(index)"
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
                        Unggah Data
                    </h5>
                </div>
                <form @submit.prevent="handleUploadData" method="post">
                    <div class="modal-body">
                        <div class="mb-0" v-if="uploadProgress < 1">
                            <label>Masukan file excel</label>
                            <input
                                type="file"
                                class="form-control"
                                @change="uploadExcelData"
                                :class="{
                                    'is-invalid': errorMessage?.messages?.file,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage?.messages?.file"
                                v-for="(error, index) in errorMessage?.messages
                                    ?.file"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="progress" v-else>
                            <div
                                class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar"
                                :aria-valuenow="uploadProgress"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                :style="{ width: uploadProgress + '%' }"
                            >
                                Harap Tunggu!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <a
                            href="/assets/files/sample-data-exam.xlsx"
                            target="_blank"
                            >Unduh Sampel Format</a
                        >
                        <div>
                            <button
                                type="button"
                                class="btn btn-sm btn-secondary me-2"
                                data-bs-dismiss="modal"
                                :disabled="isDisabled"
                            >
                                Batal
                            </button>
                            <button
                                class="btn btn-sm btn-primary"
                                v-if="!isDisabled"
                            >
                                Kirim
                            </button>
                            <button
                                class="btn btn-sm btn-primary"
                                type="button"
                                disabled
                                v-if="isDisabled"
                            >
                                <span
                                    class="spinner-border spinner-border-sm me-1"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                Harap Tunggu...
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Success :msg="message" />
    <Confirm @onDelete="onDeleteExam" />
</template>
