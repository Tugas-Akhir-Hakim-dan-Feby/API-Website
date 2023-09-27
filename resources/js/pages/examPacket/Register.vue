<script>
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import Success from "../../components/notifications/Success.vue";
import Confirm from "../../components/notifications/Confirm.vue";
import Loader from "../../components/Loader.vue";
import PaginationUtil from "../../store/utils/pagination";
import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    data() {
        return {
            examPackets: [],
            competences: [],
            examPacketDetail: {},
            errors: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
                competenceId: 0,
            },
            metaPagination: {},
            roles: [],
            msg: "",
            titleExamPacket: "",
            idExamPacket: "",
            uuid: null,
            documentPayment: null,
            isLoading: false,
            isError: false,
            isRegistered: false,
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("exam_packet_id", this.idExamPacket);
            formData.append("document_payment", this.documentPayment);

            return formData;
        },
    },
    mounted() {
        this.getExamPackets();
        this.getCompetences();
        this.getUser();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.roles = response.roles;
                })
                .catch((err) => {});
        },
        getCompetences() {
            const params = [`per_page=20`, `page=1`].join("&");

            this.$store
                .dispatch("getData", ["skill/welder", params])
                .then((response) => {
                    this.competences = response.data;
                })
                .catch((err) => {});
        },
        getExamPackets() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
                `competence_id=${this.filters.competenceId}`,
            ];

            params.push([`sort_direction=desc`]);

            this.$store
                .dispatch("getData", ["exam-packet", params.join("&")])
                .then((response) => {
                    this.isLoading = false;
                    this.examPackets = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getSchedule(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        getText(text) {
            return text.replace(/\//g, " ");
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};

            this.$store
                .dispatch("postDataUpload", ["user-exam-packet", this.formData])
                .then((response) => {
                    this.isLoading = false;
                    $("#registerExam").modal("hide");
                    $("#successModal").modal("show");
                    this.msg =
                        "Registrasi berhasil. Silahkan periksa email anda untuk mengetahui info selebihnya.";
                    this.getExamPackets();
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.message;
                });
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getExamPackets();
        },
        onCompetenceChange() {
            this.getExamPackets();
        },
        checkSchedule(schedule, timing) {
            let now = dayjs();
            schedule = dayjs(schedule).locale("id");

            if (now.isBefore(schedule, "day")) {
                return true;
            }

            return false;
        },
        onBack() {
            this.$router.push({ name: "Exam Packet" });
        },
        uploadDocumentPayment(e) {
            this.documentPayment = e.target.files[0];
        },
    },
    components: { PageTitle, Success, Pagination, Confirm, Loader },
};
</script>

<template>
    <PageTitle
        title="Daftar Uji Kompetensi"
        :isBack="true"
        @onBack="onBack"
        class="mb-0"
    >
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Exam Packet' }"
                    >Uji Kompetensi</router-link
                >
            </li>
            <li class="breadcrumb-item active">Pendaftaran</li>
        </ol></PageTitle
    >

    <div class="position-relative">
        <Loader v-if="isLoading" />
        <div
            class="d-md-flex d-block justify-content-between align-items-center mb-2"
        >
            <div>
                <select
                    class="form-select"
                    v-model="filters.competenceId"
                    @change="onCompetenceChange"
                >
                    <option selected disabled>
                        Pencarian berdasarkan kompetensi
                    </option>
                    <option value="0">Lihat semua data</option>
                    <option
                        v-for="(competence, index) in competences"
                        :key="index"
                        :value="competence.uuid"
                        v-html="competence.skillName"
                    ></option>
                </select>
            </div>

            <div class="d-md-flex justify-content-between align-items-center">
                <Pagination
                    :pagination="metaPagination"
                    @onPageChange="onPageChange($event)"
                    v-if="$can('pagination', 'Exampacket')"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-12" v-if="examPackets.length < 1">
                <div class="alert alert-warning text-center">
                    Data uji kompetensi tidak tersedia.
                </div>
            </div>
            <div
                v-else
                class="col-lg-4 col-md-6 col-sm-6"
                v-for="(examPacket, index) in examPackets"
            >
                <div class="card shadow-lg">
                    <img
                        :src="
                            '/print/image/' +
                            getText(examPacket.competenceSchema?.skillName)
                        "
                        class="card-img-top"
                        :alt="examPacket.competenceSchema?.skillName"
                    />
                    <div class="card-body">
                        <h4 class="card-title fw-bold" style="font-size: 18px">
                            {{ examPacket.competenceSchema?.skillName }}
                        </h4>
                        <h5 class="fw-semibold" style="font-size: 14px">
                            {{ examPacket.operator?.tukName }}
                        </h5>
                        <table class="table mb-0" style="font-size: 12px">
                            <tr>
                                <td>Penutupan Pendaftaran</td>
                                <td>:</td>
                                <td>
                                    {{ getSchedule(examPacket.closeSchedule) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Jadwal Uji Kompetensi</td>
                                <td>:</td>
                                <td>
                                    {{ getSchedule(examPacket.examSchedule) }}
                                </td>
                            </tr>
                        </table>
                        <p class="card-text"></p>
                        <button
                            class="badge border-0 btn-success"
                            style="cursor: auto"
                            v-if="examPacket.userRegistered"
                        >
                            Terdaftar
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#registerExam"
                            @click="
                                (idExamPacket = examPacket.uuid),
                                    (examPacketDetail = examPacket)
                            "
                            v-else
                        >
                            Daftar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center"></div>

                <div
                    class="d-md-flex justify-content-between align-items-center"
                >
                    <Pagination
                        :pagination="metaPagination"
                        @onPageChange="onPageChange($event)"
                        v-if="$can('pagination', 'Exampacket')"
                    />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>
                                Skema <br />
                                Uji Kompetensi
                            </th>
                            <th>
                                Tempat <br />
                                Uji Kompetensi
                            </th>
                            <th>Alamat Tempat Ujian</th>
                            <th>Jadwal Ujian</th>
                            <th>
                                Jadwal Penutupan <br />
                                Pendaftaran
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="examPackets.length < 1">
                            <td colspan="7" class="text-center">
                                Belum ada data uji kompetensi.
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(examPacket, index) in examPackets"
                            :key="index"
                        >
                            <th v-html="iteration(index)"></th>
                            <td
                                v-html="examPacket.competenceSchema?.skillName"
                            ></td>
                            <td v-html="examPacket.operator?.tukName"></td>
                            <td v-html="examPacket.operator?.address"></td>
                            <td
                                v-html="getSchedule(examPacket.examSchedule)"
                            ></td>
                            <td
                                v-html="getSchedule(examPacket.closeSchedule)"
                            ></td>
                            <td>
                                <button
                                    class="badge border-0 btn-success ms-2"
                                    style="cursor: auto"
                                    v-if="examPacket.userRegistered"
                                >
                                    Terdaftar
                                </button>
                                <button
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#registerExam"
                                    @click="
                                        (idExamPacket = examPacket.uuid),
                                            (examPacketDetail = examPacket)
                                    "
                                    v-else
                                >
                                    Daftar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->

    <div
        class="modal fade"
        id="registerExam"
        tabindex="-1"
        aria-labelledby="registerExamLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerExamLabel">
                        Registrasi Uji Kompetensi
                    </h5>
                </div>
                <form @submit.prevent="handleSubmit" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Peserta</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="$store.state.USER.name"
                                disabled
                            />
                        </div>
                        <div class="mb-3">
                            <label>Email Peserta</label>
                            <input
                                type="email"
                                class="form-control"
                                :value="$store.state.USER.email"
                                disabled
                            />
                        </div>
                        <div class="mb-3">
                            <label>Nama Paket Uji Kompetensi</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="
                                    examPacketDetail.competenceSchema?.skillName
                                "
                                disabled
                            />
                        </div>
                        <div class="mb-3">
                            <label>Biaya Uji Kompetensi</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="examPacketDetail.price"
                                disabled
                            />
                        </div>
                        <div class="mb-3">
                            <label>Rekening Pembayaran</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="examPacketDetail.accountNumber"
                                disabled
                            />
                        </div>
                        <div class="mb-3">
                            <label>Unggah Bukti Pembayaran</label>
                            <input
                                type="file"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.documentPayment,
                                }"
                                @change="uploadDocumentPayment"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.documentPayment"
                                v-for="(error, index) in errors.documentPayment"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary me-2"
                            data-bs-dismiss="modal"
                            :disabled="isLoading"
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
    <Success :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
