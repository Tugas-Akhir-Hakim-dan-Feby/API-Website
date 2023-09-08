<script>
export default {
    props: ["document"],
    data() {
        return {
            dataDocument: null,
            dataLogo: null,
            isDisabled: false,
            errorMessage: {},
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("document", this.dataDocument);
            formData.append("_method", "PUT");

            return formData;
        },
        formDataLogo() {
            let formData = new FormData();

            formData.append("logo", this.dataLogo);
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
        uploadDocument(e) {
            this.dataDocument = e.target.files[0];
        },
        uploadLogo(e) {
            this.dataLogo = e.target.files[0];
        },
        handleUploadDocument() {
            this.isDisabled = true;
            this.errorMessage = {};
            this.$store
                .dispatch("postDataUpload", [
                    "user/company-member/update-document/" + this.document.id,
                    this.formData,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataDocument = null;
                    $("#uploadDocument").modal("hide");

                    document.getElementById("dataDocument").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.messages;
                });
        },
        handleUploadLogo() {
            this.isDisabled = true;
            this.errorMessage = {};
            this.$store
                .dispatch("postDataUpload", [
                    "user/company-member/update-logo/" + this.document.id,
                    this.formDataLogo,
                ])
                .then((response) => {
                    this.isDisabled = false;
                    this.$emit("onSuccess", true);
                    this.dataLogo = null;
                    $("#uploadLogo").modal("hide");

                    document.getElementById("dataLogo").value = "";
                })
                .catch((error) => {
                    this.isDisabled = false;
                    this.errorMessage = error.response.data.messages;
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
                                    <td>Logo Perusahaan</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadLogo"
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p
                                                v-if="
                                                    checkFile(
                                                        document.companyLogo
                                                    )
                                                "
                                            >
                                                |
                                            </p>
                                            <a
                                                target="_blank"
                                                :href="document.companyLogo"
                                                v-if="
                                                    checkFile(
                                                        document.companyLogo
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
                                    <td>Legalitas Perusahaan</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#uploadDocument"
                                                ><i class="mdi mdi-upload"></i>
                                                Unggah</a
                                            >
                                            <p
                                                v-if="
                                                    checkFile(
                                                        document.documentLegality
                                                    )
                                                "
                                            >
                                                |
                                            </p>
                                            <a
                                                target="_blank"
                                                :href="
                                                    document.documentLegality
                                                "
                                                v-if="
                                                    checkFile(
                                                        document.documentLegality
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="uploadDocument"
        tabindex="-1"
        aria-labelledby="uploadDocumentLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadDocumentLabel">
                        Unggah Legalitas Perusahaan
                    </h5>
                </div>
                <form @submit.prevent="handleUploadDocument" method="post">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan Dokumen</label>
                            <input
                                type="file"
                                class="form-control"
                                id="dataDocument"
                                @change="uploadDocument"
                                :class="{
                                    'is-invalid': errorMessage.document,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage.document"
                                v-for="(error, index) in errorMessage.document"
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
        id="uploadLogo"
        tabindex="-1"
        aria-labelledby="uploadLogoLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadLogoLabel">
                        Unggah Logo
                    </h5>
                </div>
                <form @submit.prevent="handleUploadLogo" method="post">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label>Masukan Logo</label>
                            <input
                                type="file"
                                class="form-control"
                                id="dataLogo"
                                @change="uploadLogo"
                                :class="{
                                    'is-invalid': errorMessage.logo,
                                }"
                                :disabled="isDisabled"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errorMessage.logo"
                                v-for="(error, index) in errorMessage.logo"
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
