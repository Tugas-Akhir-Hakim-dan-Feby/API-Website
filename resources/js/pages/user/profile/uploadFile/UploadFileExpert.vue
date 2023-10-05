<script>
export default {
    props: ["document"],
    data() {
        return {
            dataPasPhoto: null,
            isDisabled: false,
            errorMessage: {},
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            if (this.dataPasPhoto) {
                formData.append("is_pas_photo", true);
                formData.append("document_pas_photo", this.dataPasPhoto);
            }
            formData.append("_method", "PUT");

            return formData;
        },
    },
    methods: {
        checkFile(file) {
            let url = window.location.origin + "/storage";
            if (file && file.length > url.length) {
                return true;
            }
            return false;
        },
        uploadPasPhoto(e) {
            this.dataPasPhoto = e.target.files[0];
        },
        uploadCertificates(e) {
            this.dataPasPhoto = e.target.files;
        },
        handleUploadPasPhoto() {
            this.isDisabled = true;
            this.errorMessage = {};

            let formData = new FormData();

            if (this.dataPasPhoto) {
                formData.append("document_pas_photo", this.dataPasPhoto);
            }
            formData.append("_method", "PUT");

            this.$store
                .dispatch("postDataUpload", [
                    "user/welder-member/update-pas-photo/" + this.document.id,
                    formData,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataPasPhoto = null;
                    $("#uploadPasPhoto").modal("hide");

                    document.getElementById("dataPasPhoto").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.messages;
                });
        },
        handleUploadCurriculumVitae() {
            this.isDisabled = true;
            this.errorMessage = {};

            let formData = new FormData();

            if (this.dataPasPhoto) {
                formData.append("document_curriculum_vitae", this.dataPasPhoto);
            }
            formData.append("_method", "PUT");

            this.$store
                .dispatch("postDataUpload", [
                    "user/welder-member/update-curriculum-vitae/" +
                        this.document.id,
                    formData,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataPasPhoto = null;
                    $("#uploadCurriculumVitae").modal("hide");

                    document.getElementById("dataPasPhoto").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.messages;
                });
        },
        handleUploadCertificateProfession() {
            this.isDisabled = true;
            this.errorMessage = {};

            let formData = new FormData();

            if (this.dataPasPhoto) {
                formData.append(
                    "document_certificate_profession",
                    this.dataPasPhoto
                );
            }
            formData.append("_method", "PUT");

            this.$store
                .dispatch("postDataUpload", [
                    "user/expert/update-certificate-profession/" +
                        this.document.id,
                    formData,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataPasPhoto = null;
                    $("#uploadCertificateProfession").modal("hide");

                    document.getElementById("dataPasPhoto").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.messages;
                });
        },
        handleUploadWorkingMail() {
            this.isDisabled = true;
            this.errorMessage = {};

            let formData = new FormData();

            if (this.dataPasPhoto) {
                formData.append("document_working_mail", this.dataPasPhoto);
            }
            formData.append("_method", "PUT");

            this.$store
                .dispatch("postDataUpload", [
                    "user/expert/update-working-mail/" + this.document.id,
                    formData,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataPasPhoto = null;
                    $("#uploadWorkingMail").modal("hide");

                    document.getElementById("dataPasPhoto").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.messages;
                });
        },
        handleUploadCareer() {
            this.isDisabled = true;
            this.errorMessage = {};

            let formData = new FormData();

            if (this.dataPasPhoto) {
                formData.append("document_career", this.dataPasPhoto);
            }
            formData.append("_method", "PUT");

            this.$store
                .dispatch("postDataUpload", [
                    "user/expert/update-career/" + this.document.id,
                    formData,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataPasPhoto = null;
                    $("#uploadCareer").modal("hide");

                    document.getElementById("dataPasPhoto").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.messages;
                });
        },
        handleUploadCertificate() {
            this.isDisabled = true;
            this.errorMessage = {};

            let formData = new FormData();

            if (this.dataPasPhoto) {
                for (let index = 0; index < this.dataPasPhoto.length; index++) {
                    formData.append(
                        `files[${index}]`,
                        this.dataPasPhoto[index]
                    );
                }
            }

            this.$store
                .dispatch("postDataUpload", [
                    "user/upload-certificate",
                    formData,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataPasPhoto = null;
                    $("#uploadCertificates").modal("hide");

                    document.getElementById("dataPasPhoto").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.message;
                });
        },
        handleDeleteCertificate(id) {
            this.isDisabled = true;

            this.$store
                .dispatch("deleteData", ["user/delete-certificate", id])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                })
                .catch((error) => {
                    this.isDisabled = false;
                });
        },
    },
};
</script>
<template>
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>Informasi Dokumen</h4>
            <p>Perbaharui informasi dokumen.</p>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-centered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Dokumen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Pas Foto Formal Berwarna</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadPasPhoto"
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p v-if="document.pasPhoto">|</p>
                                            <a
                                                target="_blank"
                                                :href="document.pasPhoto"
                                                v-if="document.pasPhoto"
                                                ><i
                                                    class="mdi mdi-download"
                                                ></i>
                                                Unduh</a
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Daftar Riwayat Hidup</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadCurriculumVitae"
                                                v-if="
                                                    checkFile(
                                                        document.curriculumVitae
                                                    ) ||
                                                    !document.curriculumVitae
                                                "
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p
                                                v-if="
                                                    checkFile(
                                                        document.curriculumVitae
                                                    )
                                                "
                                            >
                                                |
                                            </p>
                                            <a
                                                target="_blank"
                                                :href="document.curriculumVitae"
                                                v-if="
                                                    checkFile(
                                                        document.curriculumVitae
                                                    )
                                                "
                                                ><i
                                                    class="mdi mdi-download"
                                                ></i>
                                                Unduh</a
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sertifikat Profesi</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadCertificateProfession"
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p
                                                v-if="
                                                    document.certificateProfession
                                                "
                                            >
                                                |
                                            </p>
                                            <a
                                                target="_blank"
                                                :href="
                                                    document.certificateProfession
                                                "
                                                v-if="
                                                    document.certificateProfession
                                                "
                                                ><i
                                                    class="mdi mdi-download"
                                                ></i>
                                                Unduh</a
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Surat keterangan aktif bekerja</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadWorkingMail"
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p v-if="document.workingMail">|</p>
                                            <a
                                                target="_blank"
                                                :href="document.workingMail"
                                                v-if="document.workingMail"
                                                ><i
                                                    class="mdi mdi-download"
                                                ></i>
                                                Unduh</a
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Riwayat Pekerjaan</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadCareer"
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p v-if="document.career">|</p>
                                            <a
                                                target="_blank"
                                                :href="document.career"
                                                v-if="document.career"
                                                ><i
                                                    class="mdi mdi-download"
                                                ></i>
                                                Unduh</a
                                            >
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>Informasi Sertifikat</h4>
            <p>Perbaharui informasi sertifikat.</p>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <button
                        class="btn btn-sm btn-primary float-end"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadCertificates"
                    >
                        Tambah Sertifikat
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-centered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Dokumen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        certificate, index
                                    ) in document.competencyCertificates"
                                    :key="index"
                                >
                                    <td>
                                        Sertifikat Kompetensi {{ index + 1 }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                target="_blank"
                                                :href="certificate.documentPath"
                                                v-if="
                                                    checkFile(
                                                        certificate.documentPath
                                                    )
                                                "
                                                ><i
                                                    class="mdi mdi-download"
                                                ></i>
                                                Unduh</a
                                            >
                                            <p
                                                v-if="
                                                    checkFile(
                                                        certificate.documentPath
                                                    )
                                                "
                                            >
                                                |
                                            </p>
                                            <a
                                                href="#"
                                                v-if="
                                                    checkFile(
                                                        certificate.documentPath
                                                    )
                                                "
                                                @click="
                                                    handleDeleteCertificate(
                                                        certificate.id
                                                    )
                                                "
                                                ><i
                                                    class="mdi mdi-trash-can"
                                                ></i>
                                                Hapus</a
                                            >
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="uploadPasPhoto"
        tabindex="-1"
        aria-labelledby="uploadPasPhotoLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPasPhotoLabel">
                        Unggah Pas Foto
                    </h5>
                </div>
                <form @submit.prevent="handleUploadPasPhoto" method="post">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan Pas Foto</label>
                            <input
                                type="file"
                                class="form-control"
                                id="dataPasPhoto"
                                @change="uploadPasPhoto"
                                :class="{
                                    'is-invalid': errorMessage.documentPasPhoto,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage.documentPasPhoto"
                                v-for="(
                                    error, index
                                ) in errorMessage.documentPasPhoto"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
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

    <div
        class="modal fade"
        id="uploadCurriculumVitae"
        tabindex="-1"
        aria-labelledby="uploadCurriculumVitaeLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCurriculumVitaeLabel">
                        Unggah Daftar Riwayat Hidup
                    </h5>
                </div>
                <form
                    @submit.prevent="handleUploadCurriculumVitae"
                    method="post"
                >
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan Daftar Riwayat Hidup</label>
                            <input
                                type="file"
                                class="form-control"
                                id="dataCurriculumVitae"
                                @change="uploadPasPhoto"
                                :class="{
                                    'is-invalid':
                                        errorMessage.documentCurriculumVitae,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage.documentCurriculumVitae"
                                v-for="(
                                    error, index
                                ) in errorMessage.documentCurriculumVitae"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
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

    <div
        class="modal fade"
        id="uploadCertificates"
        tabindex="-1"
        aria-labelledby="uploadCertificatesLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCertificatesLabel">
                        Unggah Sertifikat
                    </h5>
                </div>
                <form @submit.prevent="handleUploadCertificate" method="post">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label
                                >Masukan Sertifikat
                                <span class="small fw-normal"
                                    >(sertifikat bisa lebih dari satu)</span
                                ></label
                            >
                            <input
                                type="file"
                                class="form-control"
                                id="dataPasPhoto"
                                @change="uploadCertificates"
                                :class="{
                                    'is-invalid': errorMessage.files,
                                }"
                                :disabled="isDisabled"
                                multiple
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage.files"
                                v-for="(error, index) in errorMessage.files"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
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

    <div
        class="modal fade"
        id="uploadCertificateProfession"
        tabindex="-1"
        aria-labelledby="uploadCertificateProfessionLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5
                        class="modal-title"
                        id="uploadCertificateProfessionLabel"
                    >
                        Unggah Sertifikat
                    </h5>
                </div>
                <form
                    @submit.prevent="handleUploadCertificateProfession"
                    method="post"
                >
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan Sertifikat Profesi </label>
                            <input
                                type="file"
                                class="form-control"
                                id="dataPasPhoto"
                                @change="uploadPasPhoto"
                                :class="{
                                    'is-invalid':
                                        errorMessage.documentCertificateProfession,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="
                                    errorMessage.documentCertificateProfession
                                "
                                v-for="(
                                    error, index
                                ) in errorMessage.documentCertificateProfession"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
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

    <div
        class="modal fade"
        id="uploadWorkingMail"
        tabindex="-1"
        aria-labelledby="uploadWorkingMailLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadWorkingMailLabel">
                        Unggah Surat
                    </h5>
                </div>
                <form @submit.prevent="handleUploadWorkingMail" method="post">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan Surat Aktif Bekerja </label>
                            <input
                                type="file"
                                class="form-control"
                                id="dataPasPhoto"
                                @change="uploadPasPhoto"
                                :class="{
                                    'is-invalid':
                                        errorMessage.documentWorkingMail,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage.documentWorkingMail"
                                v-for="(
                                    error, index
                                ) in errorMessage.documentWorkingMail"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
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

    <div
        class="modal fade"
        id="uploadCareer"
        tabindex="-1"
        aria-labelledby="uploadCareerLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCareerLabel">
                        Unggah Surat
                    </h5>
                </div>
                <form @submit.prevent="handleUploadCareer" method="post">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan Surat Aktif Bekerja </label>
                            <input
                                type="file"
                                class="form-control"
                                id="dataPasPhoto"
                                @change="uploadPasPhoto"
                                :class="{
                                    'is-invalid': errorMessage.documentCareer,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage.documentCareer"
                                v-for="(
                                    error, index
                                ) in errorMessage.documentCareer"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
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
</template>
