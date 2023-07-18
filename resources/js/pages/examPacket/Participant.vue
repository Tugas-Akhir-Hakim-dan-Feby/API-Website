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
            this.errors = {};

            this.isLoading = true;

            Object.assign(this.form, {
                userId: this.userId,
                examPacketId: this.id,
            });

            this.$store
                .dispatch("postData", [
                    "user-exam-packet/insert-value-practice",
                    this.form,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.getWelderHasExamPacket();
                    $("#insertValuePractice").modal("hide");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        handleResetKey() {
            this.isLoading = true;

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
            this.$router.push({ name: "Exam Packet" });
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
                    <span> Detail Paket </span>
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
                        v-if="$can('search', 'Adminbranch')"
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
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Peserta</th>
                            <th>Nilai Praktek</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="users?.length < 1">
                            <td colspan="4" class="text-center">
                                belum ada peserta yang daftar
                            </td>
                        </tr>
                        <tr v-else v-for="(user, index) in users" :key="index">
                            <th v-html="index + 1"></th>
                            <td v-html="user.user?.name"></td>
                            <td>{{ user.practiceValue ?? 0 }}</td>
                            <td>
                                <button
                                    @click="userId = user.user.uuid"
                                    data-bs-toggle="modal"
                                    data-bs-target="#insertValuePractice"
                                    class="btn btn-primary btn-sm me-2 text-white"
                                >
                                    Masukan Nilai Praktek
                                </button>
                                <button
                                    @click="userId = user.user.uuid"
                                    data-bs-toggle="modal"
                                    data-bs-target="#resetKeyPacket"
                                    class="btn btn-warning btn-sm me-2 text-white"
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
        id="insertValuePractice"
        tabindex="-1"
        aria-labelledby="insertValuePracticeLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertValuePracticeLabel">
                        Masukan Nilai Praktek
                    </h5>
                </div>
                <form @submit.prevent="handleSubmit" method="post">
                    <div class="modal-body">
                        <div>
                            <label
                                >Masukan Nilai Praktek
                                <span class="small text-muted"
                                    >(masukan 1 - 100)</span
                                ></label
                            >
                            <input
                                type="number"
                                class="form-control"
                                :class="{ 'is-invalid': errors.valuePractice }"
                                v-model="form.valuePractice"
                                :disabled="isLoading"
                                max="100"
                                min="0"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.valuePractice"
                                v-for="(error, index) in errors.valuePractice"
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
                            @click="(form.valuePractice = 0), (errors = {})"
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
    <Confirm @onDelete="onDeleteExam" />
</template>
