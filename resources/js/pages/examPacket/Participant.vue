<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import Show from "./Show.vue";
import util from "../../store/utils/util";

export default {
    props: ["id"],
    data() {
        return {
            userId: null,
            users: [],
            evaluation: {},
            examPacket: null,
            errors: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            metaPagination: {},
            form: {
                valuePractice: "",
            },
            documentEvaluation: null,
            date: null,
            isLoading: false,
            examId: null,
            keyPacket: "",
            msg: "",
        };
    },
    mounted() {
        this.getWelderHasExamPacket();
        this.getExamPacket();
        this.keyPacket = util.randomString(8);
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
                });
        },
        getWelderHasExamPacket() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
                `exam_packet_id=${this.id}`,
            ];

            this.$store
                .dispatch("getData", ["user-exam-packet", params.join("&")])
                .then((response) => {
                    this.isLoading = false;
                    this.users = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};

            const formData = new FormData();

            formData.append("document_evaluation", this.documentEvaluation);

            this.$store
                .dispatch("postDataUpload", [
                    "exam-packet/upload-evaluation/" + this.id,
                    formData,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.getWelderHasExamPacket();
                    this.documentEvaluation = null;
                    $("#successModal").modal("show");
                    $("#uploadEvaluation").modal("hide");
                    this.msg = "data penilaian berhasil disimpan.";
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.message;
                });
        },
        handleValidated() {
            this.isLoading = true;

            this.$store
                .dispatch("updateData", [
                    "user-exam-packet/payment-validated",
                    this.examPacket.uuid,
                    {},
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.getWelderHasExamPacket();
                    $("#validationPayment").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "data penilaian berhasil disimpan.";
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        handleResetKey() {
            this.isLoading = true;
            this.errors = {};

            const params = {
                userId: this.userId,
                examPacketId: this.id,
                keyPacket: this.keyPacket,
            };

            this.$store
                .dispatch("postData", [
                    "user-exam-packet/update-key-packet",
                    params,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.getWelderHasExamPacket();
                    $("#resetKeyPacket").modal("hide");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        handleEvaluation() {
            this.isLoading = true;
            this.errors = {};

            const params = {
                grade: this.evaluation.grade,
                notes: this.evaluation.notes,
                status: this.evaluation.status,
            };

            this.$store
                .dispatch("updateData", [
                    "user-exam-packet/update-evaluation",
                    this.evaluation.id,
                    params,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.getWelderHasExamPacket();
                    $("#updateEvaluation").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "data penilaian berhasil disimpan.";
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
        onPageChange(e) {
            this.pagination.page = e;
            this.getWelderHasExamPacket();
        },
        onSearch() {
            setTimeout(() => {
                this.getWelderHasExamPacket();
            }, 1000);
        },
        onBack(e) {
            this.$router.push({ name: "Exam Packet Detail" });
        },
        uploadEvaluation(e) {
            this.documentEvaluation = e.target.files[0];
        },
    },
    components: { PageTitle, Success, Show, Confirm, Loader, Pagination },
};
</script>

<template>
    <PageTitle
        v-if="examPacket"
        :title="`Daftar Peserta Uji Kompetensi`"
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
            <li class="breadcrumb-item">
                <router-link
                    :to="{
                        name: 'Exam Packet Detail',
                        params: { id: id },
                    }"
                >
                    <span> Detail Ujian </span>
                </router-link>
            </li>
            <li class="breadcrumb-item active">Peserta</li>
        </ol>
    </PageTitle>

    <Show
        v-if="examPacket"
        :examPacket="examPacket"
        @onSuccessEdit="onSuccessEdit"
    />

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div
            class="card-header d-flex justify-content-between align-items-center"
        >
            <h4>Daftar Peserta</h4>
            <div class="d-md-flex justify-content-between align-items-center">
                <div class="me-md-2 me-0">
                    <input
                        type="search"
                        class="form-control"
                        placeholder="pencarian"
                        @keyup="onSearch"
                        v-model="filters.search"
                        v-if="$can('search', 'Exampacket')"
                    />
                </div>

                <Pagination
                    :pagination="metaPagination"
                    @onPageChange="onPageChange($event)"
                    v-if="$can('pagination', 'Exampacket')"
                />
            </div>
        </div>
        <div class="card-body">
            <div v-if="users.length > 0">
                <a
                    v-if="examPacket"
                    target="_blank"
                    :href="`/export/participant/${examPacket.uuid}`"
                    class="btn btn-sm btn-primary mb-3 me-2"
                >
                    Unduh Data Peserta
                </a>
                <!-- <a
                    data-bs-toggle="modal"
                    data-bs-target="#uploadEvaluation"
                    class="btn btn-sm btn-primary mb-3"
                    v-if="$can('evaluation', 'Exampacket')"
                >
                    Unggah Data Penilaian
                </a> -->
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Peserta</th>
                            <th>Nilai Ujian Teori</th>
                            <th>Penilaian Kompetensi</th>
                            <th>Catatan</th>
                            <th>Status Validasi</th>
                            <th v-if="$can('evaluation', 'Exampacket')">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="users?.length < 1">
                            <td
                                :colspan="
                                    $can('evaluation', 'Exampacket') ? 8 : 7
                                "
                                class="text-center"
                            >
                                belum ada peserta yang daftar
                            </td>
                        </tr>
                        <tr v-else v-for="(user, index) in users" :key="index">
                            <th v-html="index + 1"></th>
                            <td v-html="user.user?.name"></td>
                            <td>
                                <tr>
                                    <td>Jawaban Benar</td>
                                    <td>:</td>
                                    <td v-html="user.correctAnswer"></td>
                                </tr>
                                <tr>
                                    <td>Jawaban Salah</td>
                                    <td>:</td>
                                    <td v-html="user.wrongAnswer"></td>
                                </tr>
                                <tr>
                                    <td>Persentase</td>
                                    <td>:</td>
                                    <td
                                        v-html="user.correctPrecentage + '%'"
                                    ></td>
                                </tr>
                            </td>
                            <td><p v-html="user.grade"></p></td>
                            <td v-html="user.notes ?? '-'"></td>
                            <td>
                                <span
                                    class="badge bg-success"
                                    v-if="user.validatedAt"
                                    >LUNAS</span
                                >
                                <button
                                    @click="examPacket = user"
                                    data-bs-toggle="modal"
                                    data-bs-target="#validationPayment"
                                    class="btn btn-success btn-sm me-2 mb-2 text-white"
                                    v-if="
                                        !user.validatedAt &&
                                        $can('evaluation', 'Exampacket')
                                    "
                                >
                                    Validasi Pembayaran
                                </button>
                            </td>
                            <td v-if="$can('evaluation', 'Exampacket')">
                                <button
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateEvaluation"
                                    class="btn btn-warning btn-sm me-2 mb-2 text-white"
                                    @click="
                                        (evaluation.grade = user.grade),
                                            (evaluation.notes = user.notes),
                                            (evaluation.status = user.status),
                                            (evaluation.id = user.uuid)
                                    "
                                    v-if="$can('evaluation', 'Exampacket')"
                                >
                                    Edit Penilaian
                                </button>
                                <button
                                    @click="userId = user.user.uuid"
                                    data-bs-toggle="modal"
                                    data-bs-target="#resetKeyPacket"
                                    class="btn btn-danger btn-sm me-2 mb-2 text-white"
                                    v-if="$can('reset-key', 'Exampacket')"
                                >
                                    Reset Kunci Paket
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
        id="resetKeyPacket"
        tabindex="-1"
        aria-labelledby="resetKeyPacketLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetKeyPacketLabel">
                        Reset Kunci Paket
                    </h5>
                </div>
                <form @submit.prevent="handleResetKey" method="post">
                    <div class="modal-body">
                        <div>
                            <label>Kunci Paket </label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="keyPacket"
                                readonly
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Kirim
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="updateEvaluation"
        tabindex="-1"
        aria-labelledby="updateEvaluationLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateEvaluationLabel">
                        Perbaharui Penilaian
                    </h5>
                </div>
                <form @submit.prevent="handleEvaluation" method="post">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Penilaian</label>
                            <textarea
                                class="form-control"
                                :class="{ 'is-invalid': errors.grade }"
                                :disabled="isLoading"
                                v-model="evaluation.grade"
                            ></textarea>
                            <div
                                class="invalid-feedback"
                                v-if="errors.grade"
                                v-for="(error, index) in errors.grade"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Catatan</label>
                            <textarea
                                class="form-control"
                                :class="{ 'is-invalid': errors.notes }"
                                :disabled="isLoading"
                                v-model="evaluation.notes"
                            ></textarea>
                            <div
                                class="invalid-feedback"
                                v-if="errors.notes"
                                v-for="(error, index) in errors.notes"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Status Kelulusan</label>
                            <select
                                class="form-select"
                                v-model="evaluation.status"
                                :disabled="isLoading"
                                :class="{ 'is-invalid': errors.status }"
                            >
                                <option value="3">Ya</option>
                                <option value="2">Tidak</option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.status"
                                v-for="(error, index) in errors.status"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Kirim
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="uploadEvaluation"
        tabindex="-1"
        aria-labelledby="uploadEvaluationLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadEvaluationLabel">
                        Masukan Hasil Penilaian
                    </h5>
                </div>
                <form @submit.prevent="handleSubmit" method="post">
                    <div class="modal-body">
                        <div>
                            <label>Masukan Penilaian </label>
                            <input
                                type="file"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.documentEvaluation,
                                }"
                                :disabled="isLoading"
                                @change="uploadEvaluation"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.documentEvaluation"
                                v-for="(
                                    error, index
                                ) in errors.documentEvaluation"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Kirim
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="validationPayment"
        tabindex="-1"
        aria-labelledby="validationPaymentLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validationPaymentLabel">
                        Validasi Pembayaran
                    </h5>
                </div>
                <form @submit.prevent="handleValidated" method="post">
                    <div class="modal-body">
                        <a
                            v-if="examPacket"
                            :href="examPacket.payment"
                            target="_blank"
                        >
                            <img
                                :src="examPacket.payment"
                                style="width: 100%"
                            />
                        </a>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Validasi
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Success :msg="msg" />
    <Confirm @onDelete="onDeleteExam" />
</template>
