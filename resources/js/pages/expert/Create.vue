<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
import Util from "../../store/utils/util";

export default {
    components: { PageTitle, Success },
    data() {
        return {
            isLoading: false,
            isWarning: false,
            user: {},
            errors: {},
            form: {
                instance: "",
                documentCertificateProfession: "",
                documentCertificateCompetency: "",
                documentWorkingMail: "",
                documentCareer: "",
            },
        };
    },
    mounted() {
        this.getUser();
        Util.removeInvalidClass();
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("instance", this.form.instance);
            formData.append(
                "document_certificate_profession",
                this.form.documentCertificateProfession
            );
            formData.append(
                "document_certificate_competency",
                this.form.documentCertificateCompetency
            );
            formData.append(
                "document_working_mail",
                this.form.documentWorkingMail
            );
            formData.append("document_career", this.form.documentCareer);

            return formData;
        },
    },
    methods: {
        uploadCertificateProfession(e) {
            this.form.documentCertificateProfession = e.target.files[0];
        },
        uploadCertificateCompetency(e) {
            this.form.documentCertificateCompetency = e.target.files[0];
        },
        uploadWorkingMail(e) {
            this.form.documentWorkingMail = e.target.files[0];
        },
        uploadCareer(e) {
            this.form.documentCareer = e.target.files[0];
        },
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.user = response.data;
                })
                .catch((error) => {});
        },
        handleSubmit() {
            this.isLoading = true;
            this.$store
                .dispatch("postDataUpload", ["user/expert", this.formData])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (
                        error.response.data.status == "WARNING" &&
                        error.response.data.statusCode == 400
                    ) {
                        this.isWarning = true;
                    } else {
                        this.errors = error.response.data.messages;
                    }
                });
        },
    },
};
</script>
<template>
    <PageTitle title="Daftar Pakar" />

    <div
        class="alert alert-warning alert-dismissible"
        role="alert"
        v-if="isWarning"
    >
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
        Data anda sudah terdaftar, harap tunggu konfirmasi dari Admin!
    </div>

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label>Instansi</label>
                    <input
                        type="text"
                        class="form-control form-validation"
                        v-model="form.instance"
                        :class="{ 'is-invalid': errors.instance }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.instance"
                        v-for="(error, index) in errors.instance"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label
                        >Sertifikat Kompetensi Bidang Pengelasan Tingkat
                        Nasional atau International</label
                    ><input
                        type="file"
                        class="form-control form-validation"
                        :class="{
                            'is-invalid': errors.documentCertificateCompetency,
                        }"
                        :disabled="isLoading"
                        @change="uploadCertificateCompetency"
                        accept=".pdf"
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
                <div class="mb-3">
                    <label
                        >Riwayat Pengalaman Bekerja atau Penelitian Bidang
                        Pengelasan 10 Tahun Terakhir</label
                    ><input
                        type="file"
                        class="form-control form-validation"
                        :class="{
                            'is-invalid': errors.documentCareer,
                        }"
                        :disabled="isLoading"
                        @change="uploadCareer"
                        accept=".pdf"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentCareer"
                        v-for="(error, index) in errors.documentCareer"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label
                        >Surat Keterangan Aktif Bekerja Dalam Bidang Pengelasan </label
                    ><input
                        type="file"
                        class="form-control form-validation"
                        :class="{
                            'is-invalid': errors.documentWorkingMail,
                        }"
                        :disabled="isLoading"
                        @change="uploadWorkingMail"
                        accept=".pdf"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentWorkingMail"
                        v-for="(
                            error, index
                        ) in errors.documentCertificateCompetency"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Memiliki Sertifikat atau Ijazah Gelar Profesi</label
                    ><input
                        type="file"
                        class="form-control form-validation"
                        :class="{
                            'is-invalid': errors.documentCertificateProfession,
                        }"
                        :disabled="isLoading"
                        @change="uploadCertificateProfession"
                        accept=".pdf"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentCertificateProfession"
                        v-for="(
                            error, index
                        ) in errors.documentCertificateProfession"
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
        :msg="'data berhasil diregistrasi, harap tunggu konfirmasi dari admin!'"
    />
</template>
