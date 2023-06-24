<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
import Util from "../../store/utils/util";
import jsCookie from "js-cookie";
import "select2";

export default {
    data() {
        return {
            form: {
                name: "",
                schedule: "",
                startTime: "",
                endTime: "",
                practiceExamAddress: "",
                personResponsible: [],
            },
            experUsers: [],
            minDate: new Date().toISOString().split("T")[0],
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        this.util();
        // this.getExpertUsers ();
        Util.removeInvalidClass();
    },
    methods: {
        getExpertUsers() {
            this.$store
                .dispatch("getData", ["user/expert", {}])
                .then((response) => {
                    this.experUsers = response.data.map((user) => ({
                        id: user.uuid,
                        text: user.name,
                    }));
                })
                .catch((err) => {});
        },
        handleSubmit() {
            this.isLoading = true;
            this.$store
                .dispatch("postData", ["exam-packet", this.form])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        util() {
            $(this.$refs.personResponsible).select2({
                ajax: {
                    url: `${this.$store.state.BASE_URL}/api/v1/user/expert`,
                    dataType: "json",
                    headers: {
                        Authorization: "Bearer " + jsCookie.get("token"),
                    },
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            per_page: 5,
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data.map((user) => ({
                                id: user.uuid,
                                text: user.name,
                            })),
                        };
                    },
                    cache: true,
                },
                minimumInputLength: 1,
            });
            $(this.$refs.personResponsible).on("change", () => {
                this.form.personResponsible = $(
                    this.$refs.personResponsible
                ).val();
                $(".select2-hidden-accessible").removeClass("is-invalid");
            });
        },
        onCancel() {
            this.$router.back(-1);
        },
    },
    components: { PageTitle, Success },
};
</script>

<template>
    <PageTitle title="Tambah Paket">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Exam Packet' }">
                    <span> Uji Kompetensi </span>
                </router-link>
            </li>
            <li class="breadcrumb-item active">Tambah Paket</li>
        </ol>
    </PageTitle>
    <div class="row">
        <div class="col-lg-6">
            <form @submit.prevent="handleSubmit" method="post">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <label for="name">Nama Paket</label>
                            <input
                                type="text"
                                class="form-control form-validation"
                                id="name"
                                :class="{ 'is-invalid': errors.name }"
                                v-model="form.name"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.name"
                                v-for="(error, index) in errors.name"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="schedule">Jadwal Ujian</label>
                            <input
                                type="date"
                                class="form-control form-validation"
                                id="schedule"
                                :class="{ 'is-invalid': errors.schedule }"
                                v-model="form.schedule"
                                :disabled="isLoading"
                                :min="minDate"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.schedule"
                                v-for="(error, index) in errors.schedule"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Tenggat Pengerjaan</label>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <input
                                        type="time"
                                        class="form-control form-validation"
                                        id="startTime"
                                        :class="{
                                            'is-invalid': errors.startTime,
                                        }"
                                        v-model="form.startTime"
                                        :disabled="isLoading"
                                    />
                                    <small>* masukan waktu mulai</small>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.startTime"
                                        v-for="(
                                            error, index
                                        ) in errors.startTime"
                                        :key="index"
                                    >
                                        {{ error }}
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <input
                                        type="time"
                                        class="form-control form-validation"
                                        id="endTime"
                                        :class="{
                                            'is-invalid': errors.endTime,
                                        }"
                                        v-model="form.endTime"
                                        :disabled="isLoading"
                                    />
                                    <small>* masukan waktu selesai</small>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.endTime"
                                        v-for="(error, index) in errors.endTime"
                                        :key="index"
                                    >
                                        {{ error }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="practice_exam_address"
                                    >Alamat Tempat Ujian</label
                                >
                                <textarea
                                    class="form-control form-validation"
                                    id="practice_exam_address"
                                    :class="{
                                        'is-invalid':
                                            errors.practiceExamAddress,
                                    }"
                                    v-model="form.practiceExamAddress"
                                    :disabled="isLoading"
                                ></textarea>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.practiceExamAddress"
                                    v-for="(
                                        error, index
                                    ) in errors.practiceExamAddress"
                                    :key="index"
                                >
                                    {{ error }}
                                </div>
                            </div>
                            <div class="mb-2">
                                <label
                                    >Penanggung Jawab
                                    <span class="text-muted small"
                                        >*diisi oleh pakar</span
                                    ></label
                                >
                                <select
                                    multiple
                                    ref="personResponsible"
                                    class="select2-hidden-accessible"
                                    :class="{
                                        'is-invalid': errors.personResponsible,
                                    }"
                                    :disabled="isLoading"
                                ></select>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.personResponsible"
                                    v-for="(
                                        error, index
                                    ) in errors.personResponsible"
                                    :key="index"
                                >
                                    {{ error }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button
                            class="btn btn-sm btn-secondary"
                            @click="onCancel"
                            :disabled="isLoading"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-success"
                            v-if="!isLoading"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-sm btn-success"
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
                </div>
            </form>
        </div>
    </div>

    <Success
        :url="{ name: 'Exam Packet' }"
        :msg="'data berhasil ditambahkan.'"
    />
</template>
