<script>
import Loader from "../../../../components/Loader.vue";

export default {
    props: ["document", "isLoading"],
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
    components: { Loader },
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
                <Loader v-if="isDisabled" />
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
                                <tr>
                                    <td>Pas Foto Formal Berwarna</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadPasPhoto"
                                                v-if="
                                                    checkFile(document.pasPhoto)
                                                "
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p
                                                v-if="
                                                    checkFile(document.pasPhoto)
                                                "
                                            >
                                                |
                                            </p>
                                            <a
                                                target="_blank"
                                                :href="document.pasPhoto"
                                                v-if="
                                                    checkFile(document.pasPhoto)
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
                                <!-- <tr
                                    v-if="
                                        document.competencyCertificates
                                            ?.length < 1
                                    "
                                >
                                    <td>Sertifikat Kompetensi</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadDocument"
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                        </div>
                                    </td>
                                </tr> -->
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
                                accept="image/jpg, image/png, image/jpeg"
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
                                accept=".pdf"
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
                                accept=".pdf"
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
</template>
