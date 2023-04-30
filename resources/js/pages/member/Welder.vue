<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
export default {
    components: { PageTitle, Success },
    data() {
        return {
            form: {
                welderSkillId: "",
                residentIdCard: "",
                dateBirth: "",
                birthPlace: "",
                workingStatus: "",
                documentCertificateSchool: "",
                documentPasPhoto: "",
                documentCertificateCompetency: "",
            },
            welderSkills: [],
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        this.getWelderSkills();
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("welder_skill_id", this.form.welderSkillId);
            formData.append("resident_id_card", this.form.residentIdCard);
            formData.append("date_birth", this.form.dateBirth);
            formData.append("birth_place", this.form.birthPlace);
            formData.append("working_status", this.form.workingStatus);
            formData.append(
                "document_certificate_school",
                this.form.documentCertificateSchool
            );
            formData.append("document_pas_photo", this.form.documentPasPhoto);
            formData.append(
                "document_certificate_competency",
                this.form.documentCertificateCompetency
            );

            return formData;
        },
    },
    methods: {
        onBack() {
            this.$router.push({ name: "Member" });
        },
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", {}])
                .then((response) => {
                    this.welderSkills = response.data;
                })
                .catch((error) => {});
        },
        handleSubmit() {
            this.isLoading = true;
            this.$store
                .dispatch("postDataUpload", [
                    "user/welder-member",
                    this.formData,
                ])
                .then((response) => {
                    this.isLoading = false;
                    console.log(response);
                })
                .catch((error) => {
                    this.isLoading = false;

                    this.errors = error.response.data.messages;

                    if (
                        error.response.data.status == "ERROR" &&
                        error.response.data.statusCode == 500
                    ) {
                        this.isError = true;
                        this.msg = error.response.data.message;
                    }
                });
        },
        uploadCertificateSchool(e) {
            this.form.documentCertificateSchool = e.target.files[0];
        },
        uploadPasPhoto(e) {
            this.form.documentPasPhoto = e.target.files[0];
        },
        uploadCertificateCompetency(e) {
            this.form.documentCertificateCompetency = e.target.files[0];
        },
    },
};
</script>

<template>
    <PageTitle title="Daftar Member Welder" :isBack="true" @onBack="onBack" />

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label>NIK</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.residentIdCard"
                        :class="{ 'is-invalid': errors.residentIdCard }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.residentIdCard"
                        v-for="(error, index) in errors.residentIdCard"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Tempat Lahir</label
                    ><input
                        type="text"
                        class="form-control"
                        v-model="form.birthPlace"
                        :class="{ 'is-invalid': errors.birthPlace }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.birthPlace"
                        v-for="(error, index) in errors.birthPlace"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Tanggal Lahir</label
                    ><input
                        type="date"
                        class="form-control"
                        v-model="form.dateBirth"
                        :class="{ 'is-invalid': errors.dateBirth }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.dateBirth"
                        v-for="(error, index) in errors.dateBirth"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Status Bekerja</label>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            value="1"
                            v-model="form.workingStatus"
                            :class="{ 'is-invalid': errors.workingStatus }"
                            :disabled="isLoading"
                        /><label class="form-check-label"> Ya </label>
                    </div>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            value="0"
                            v-model="form.workingStatus"
                            :class="{ 'is-invalid': errors.workingStatus }"
                            :disabled="isLoading"
                        /><label class="form-check-label"> Tidak </label>
                    </div>
                    <div
                        class="invalid-feedback"
                        v-if="errors.workingStatus"
                        v-for="(error, index) in errors.workingStatus"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Jenis Kompetensi</label
                    ><select
                        class="form-select"
                        v-model="form.welderSkillId"
                        :class="{ 'is-invalid': errors.welderSkillId }"
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
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Pas Foto Formal Berwarna</label
                    ><input
                        type="file"
                        class="form-control"
                        :class="{ 'is-invalid': errors.documentPasPhoto }"
                        :disabled="isLoading"
                        @change="uploadPasPhoto"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentPasPhoto"
                        v-for="(error, index) in errors.documentPasPhoto"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Ijazah Pendidikan Formal</label
                    ><input
                        type="file"
                        class="form-control"
                        :class="{
                            'is-invalid': errors.documentCertificateSchool,
                        }"
                        :disabled="isLoading"
                        @change="uploadCertificateSchool"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentCertificateSchool"
                        v-for="(
                            error, index
                        ) in errors.documentCertificateSchool"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label
                        >Sertifikat Pelatihan/Kompetensi Keahlian Pengelasan </label
                    ><input
                        type="file"
                        class="form-control"
                        :class="{
                            'is-invalid': errors.documentCertificateCompetency,
                        }"
                        :disabled="isLoading"
                        @change="uploadCertificateCompetency"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentCertificateCompetency"
                        v-for="(
                            error, index
                        ) in errors.documentCertificateCompetency"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
            </div>
            <div class="card-footer border-top d-flex justify-content-between">
                <router-link :to="{ name: 'Member' }" class="btn btn-secondary"
                    >Batal</router-link
                >
                <button class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>

    <Success
        :url="{ name: 'Dashboard' }"
        :msg="'data berhasil diregistrasi.'"
    />
</template>
