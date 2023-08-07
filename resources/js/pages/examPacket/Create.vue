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
                examSchedule: "",
                closeSchedule: "",
                startTime: "",
                endTime: "",
                welderSkillId: "",
                operatorId: "",
                price: "",
                accountNumber: "",
                documentCertificate: "",
            },
            welderSkills: [],
            minDate: new Date().toISOString().split("T")[0],
            errors: {},
            user: {},
            isLoading: false,
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("name", this.form.name);
            formData.append("exam_schedule", this.form.examSchedule);
            formData.append("close_schedule", this.form.closeSchedule);
            formData.append("start_time", this.form.startTime);
            formData.append("end_time", this.form.endTime);
            formData.append("welder_skill_id", this.form.welderSkillId);
            formData.append("price", this.form.price);
            formData.append("account_number", this.form.accountNumber);
            formData.append("operator_id", this.user.operator.uuid);
            formData.append(
                "document_certificate",
                this.form.documentCertificate
            );

            return formData;
        },
    },
    mounted() {
        this.getTuk();
        this.getUser();
        this.getWelderSkills();
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
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.user = response.user;
                });
        },
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", ""])
                .then((response) => {
                    this.welderSkills = response.data;
                });
        },
        handleSubmit() {
            this.isLoading = true;

            this.$store
                .dispatch("postDataUpload", ["exam-packet", this.formData])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        getTuk() {
            $(this.$refs.tuk).select2({
                ajax: {
                    url: `${this.$store.state.BASE_URL}/api/v1/user/operator`,
                    dataType: "json",
                    headers: {
                        Authorization: "Bearer " + jsCookie.get("token"),
                    },
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            per_page: 10,
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data.map((user) => ({
                                id: user.operator?.uuid,
                                text: `${user.operator?.tuk_type} / ${user.operator?.tuk_name} / ${user.operator?.tuk_code}`,
                            })),
                        };
                    },
                    cache: true,
                },
            });
            $(this.$refs.tuk).on("change", () => {
                this.form.operatorId = $(this.$refs.tuk).val();
                $(".select2-hidden-accessible").removeClass("is-invalid");
            });
        },
        uploadDocumentCertificate(e) {
            this.form.documentCertificate = e.target.files[0];
        },
        onCancel() {
            this.$router.back(-1);
        },
    },
    components: { PageTitle, Success },
};
</script>

<template>
    <PageTitle title="Tambah Paket" :isBack="true" @onBack="onCancel">
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
        <div class="col-lg-12">
            <form @submit.prevent="handleSubmit" method="post">
                <div class="card">
                    <div class="card-body">
                        <!-- <div class="mb-2" v-if="user.roleId != 8">
                            <label for="name">TUK</label>
                            <select
                                class="form-select form-validation select2-hidden-accessible"
                                ref="tuk"
                                :class="{ 'is-invalid': errors.operatorId }"
                                :disabled="isLoading"
                            ></select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.operatorId"
                                v-for="(error, index) in errors.operatorId"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div> -->
                        <div class="mb-2">
                            <label for="name">Skema Kompetensi</label>
                            <select
                                class="form-select form-validation"
                                :class="{ 'is-invalid': errors.welderSkillId }"
                                v-model="form.welderSkillId"
                                :disabled="isLoading"
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="(welderSkill, index) in welderSkills"
                                    :key="index"
                                    :value="welderSkill.uuid"
                                    v-html="welderSkill.skillName"
                                ></option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.welderSkillId"
                                v-for="(error, index) in errors.welderSkillId"
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
                                :class="{ 'is-invalid': errors.examSchedule }"
                                v-model="form.examSchedule"
                                :disabled="isLoading"
                                :min="minDate"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.examSchedule"
                                v-for="(error, index) in errors.examSchedule"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="schedule"
                                >Jadwal Penutupan Pendaftaran</label
                            >
                            <input
                                type="date"
                                class="form-control form-validation"
                                id="schedule"
                                :class="{ 'is-invalid': errors.closeSchedule }"
                                v-model="form.closeSchedule"
                                :disabled="isLoading"
                                :min="minDate"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.closeSchedule"
                                v-for="(error, index) in errors.closeSchedule"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div>
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
                        </div>
                        <div class="mb-2">
                            <label for="">Harga Uji Kompetensi</label>
                            <input
                                type="number"
                                class="form-control form-validation"
                                id=""
                                :class="{ 'is-invalid': errors.price }"
                                v-model="form.price"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.price"
                                v-for="(error, index) in errors.price"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="">Nomor Rekening</label>
                            <input
                                type="text"
                                class="form-control form-validation"
                                :class="{ 'is-invalid': errors.accountNumber }"
                                v-model="form.accountNumber"
                                :disabled="isLoading"
                                placeholder="BCA-081219911"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.accountNumber"
                                v-for="(error, index) in errors.accountNumber"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="">Unggah Sertifikat</label>
                            <input
                                type="file"
                                class="form-control form-validation"
                                id=""
                                :class="{
                                    'is-invalid': errors.documentCertificate,
                                }"
                                :disabled="isLoading"
                                @change="uploadDocumentCertificate"
                            />
                            <small
                                >unduh contoh penulisan
                                <a href="/assets/files/example-certificate.docx"
                                    >sertifikat</a
                                ></small
                            >.
                            <div
                                class="invalid-feedback"
                                v-if="errors.documentCertificate"
                                v-for="(
                                    error, index
                                ) in errors.documentCertificate"
                                :key="index"
                            >
                                {{ error }}
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
