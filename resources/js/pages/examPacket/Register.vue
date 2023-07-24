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
            errors: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            metaPagination: {},
            roles: [],
            msg: "",
            titleExamPacket: "",
            idExamPacket: "",
            uuid: null,
            isLoading: false,
            isError: false,
            isRegistered: false,
        };
    },
    mounted() {
        this.getExamPackets();
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
        getExamPackets() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
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
        getCheckExamPacket(examPacketId) {
            this.$store
                .dispatch("showData", ["user-exam-packet/check", examPacketId])
                .then((response) => {
                    this.isRegistered = response;
                });

            return this.isRegistered;
        },
        getSchedule(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        handleSubmit() {
            this.isLoading = true;

            const form = {
                examPacketId: this.idExamPacket,
            };

            this.$store
                .dispatch("postData", ["user-exam-packet", form])
                .then((response) => {
                    this.isLoading = false;
                    $("#registerExam").modal("hide");
                    $("#successModal").modal("show");
                    this.msg =
                        "Registrasi berhasil. Silahkan periksa email anda untuk mengetahui info selebihnya.";
                    this.getUser();
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        onPageChange(e) {
            this.pagination.page = e;
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
    },
    components: { PageTitle, Success, Pagination, Confirm, Loader },
};
</script>

<template>
    <PageTitle title="Daftar Uji Kompetensi" :isBack="true" @onBack="onBack" />

    <div class="card position-relative">
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
                            <td colspan="5" class="text-center">
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
                                    v-if="getCheckExamPacket(examPacket.uuid)"
                                >
                                    Terdaftar
                                </button>
                                <button
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#registerExam"
                                    @click="
                                        (titleExamPacket =
                                            examPacket.competenceSchema
                                                ?.skillName),
                                            (idExamPacket = examPacket.uuid)
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
    </div>

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
                                :value="titleExamPacket"
                                disabled
                            />
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
