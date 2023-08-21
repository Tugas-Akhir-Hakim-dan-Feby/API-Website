<script>
import Loader from "../../components/Loader.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import DataPersonal from "../user/DataPersonal.vue";
import util from "../../store/utils/util";
import Experience from "../../database/experience.json";

export default {
    props: ["slug"],
    data() {
        return {
            isLoading: false,
            isRegistered: 0,
            isNullPersonalData: false,
            form: {
                experience: "",
                promote: "",
                jobVacancyId: "",
                documentPoliceRecord: null,
            },
            jobVacancy: {},
            experiences: [],
            errors: {},
        };
    },
    watch: {
        isNullPersonalData(newVal) {
            this.isNullPersonalData = newVal;
        },
    },
    mounted() {
        this.getUser();
        this.getExperience();
        this.getJobVacancy();
    },
    methods: {
        getUser() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.getDataPersonal(response.user);
                })
                .catch((err) => {});
        },
        getExperience() {
            this.experiences = Experience;
        },
        getDataPersonal(user) {
            this.$store
                .dispatch("showData", ["user/personal-data", user.uuid])
                .then((response) => {
                    this.isLoading = false;
                })
                .catch((error) => {
                    if (
                        error.response.data.statusCode == 404 &&
                        error.response.data.status == "WARNING"
                    ) {
                        this.isLoading = false;
                        this.isNullPersonalData = true;
                    }
                });
        },
        getJobVacancy() {
            this.$store
                .dispatch("showData", ["job-vacancy/show-by-slug", this.slug])
                .then((response) => {
                    this.jobVacancy = response.data;
                    this.checkJobRegister(response.data.uuid);
                });
        },
        getRupiah(amount) {
            return util.getRupiah(amount);
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};

            let formData = new FormData();

            formData.append("experience", this.form.experience);
            formData.append("promote", this.form.promote);
            formData.append("job_vacancy_id", this.jobVacancy.uuid);

            if (this.form.documentPoliceRecord) {
                formData.append(
                    "document_police_record",
                    this.form.documentPoliceRecord
                );
            }

            this.$store
                .dispatch("postDataUpload", ["register-job", formData])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        checkJobRegister(jobVacancyId) {
            this.$store
                .dispatch("showData", ["register-job/check", jobVacancyId])
                .then((response) => {
                    let isRegistered = response;
                    if (isRegistered) {
                        this.$router.back();
                    }
                });
        },
        onSuccess() {
            this.isNullPersonalData = false;
            this.getUser();
        },
        uploadPoliceRecord(e) {
            this.form.documentPoliceRecord = e.target.files[0];
        },
    },
    components: { PageTitle, Loader, Success, DataPersonal },
};
</script>

<template>
    <PageTitle
        title="Lamar Pekerjaan"
        :isBack="true"
        @onBack="onBack()"
        v-if="!isNullPersonalData"
    />

    <div v-if="!isNullPersonalData" class="position-relative">
        <Loader v-if="isLoading" />
        <form @submit.prevent="handleSubmit" method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <img
                                        :src="
                                            jobVacancy.companyMember
                                                ?.companyLogo
                                        "
                                        :alt="
                                            jobVacancy.companyMember
                                                ?.companyName
                                        "
                                        style="height: 50px; width: 100%"
                                    />
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <h5 class="mt-md-0">
                                        {{ jobVacancy.welderSkill?.skillName }}
                                    </h5>
                                    <p>
                                        {{
                                            jobVacancy.companyMember
                                                ?.companyName
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="fw-bold m-0">Perkiraan Gaji</p>
                                <p>{{ getRupiah(jobVacancy.salary) }}</p>
                            </div>
                            <div>
                                <p class="fw-bold m-0">Jenis Pekerjaan</p>
                                <p>{{ jobVacancy.workType }}</p>
                            </div>
                            <div>
                                <p class="fw-bold m-0">Penempatan</p>
                                <p>{{ jobVacancy.placement }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label
                                    >Berapa tahun pengalaman kerjamu sebagai
                                    <span class="text-lowercase">{{
                                        jobVacancy.welderSkill?.skillName
                                    }}</span
                                    >?</label
                                >
                                <select
                                    class="form-select"
                                    v-model="form.experience"
                                    :class="{ 'is-invalid': errors.experience }"
                                    :disabled="isLoading"
                                >
                                    <option disabled selected></option>
                                    <option
                                        v-for="(
                                            experience, index
                                        ) in experiences"
                                        :key="index"
                                        :value="experience.value"
                                    >
                                        {{ experience.value }}
                                    </option>
                                </select>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.experience"
                                    v-for="(error, index) in errors.experience"
                                    :key="index"
                                >
                                    {{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Promosikan diri Anda! </label>
                                <textarea
                                    class="form-control"
                                    rows="8"
                                    v-model="form.promote"
                                    :class="{ 'is-invalid': errors.promote }"
                                    :disabled="isLoading"
                                    placeholder="Beritahu perusahaan mengapa Anda paling cocok untuk posisi ini. Sebutkan keterampilan khusus dan bagaimana Anda berkontribusi. Hindari hal generik seperti Saya bertanggung jawab."
                                ></textarea>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.promote"
                                    v-for="(error, index) in errors.promote"
                                    :key="index"
                                >
                                    {{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Unggah Dokumen SKCK! </label>
                                <input
                                    type="file"
                                    class="form-control"
                                    @change="uploadPoliceRecord"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.documentPoliceRecord"
                                    v-for="(
                                        error, index
                                    ) in errors.documentPoliceRecord"
                                    :key="index"
                                >
                                    {{ error }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button
                                class="btn btn-sm btn-primary"
                                v-if="!isLoading"
                            >
                                Kirim Lamaran
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
                    </div>
                </div>
            </div>
        </form>
    </div>

    <DataPersonal v-if="isNullPersonalData" @onSuccess="onSuccess" />
    <Success
        v-if="!isNullPersonalData"
        :url="{ name: 'Job Vacancy' }"
        :msg="'lamaran berhasil dikirim.'"
    />
</template>
