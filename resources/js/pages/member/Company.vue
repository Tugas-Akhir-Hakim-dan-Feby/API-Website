<script>
import PageTitle from "../../components/PageTitle.vue";
export default {
    data() {
        return {
            form: {
                companyName: "",
                companyDirector: "",
                companyAddress: "",
                companyProfile: "",
                companyEmail: "",
                phone: "",
                facsmile: "",
                documentCompanyLegality: "",
                documentCompanyLogo: "",
            },
            welderSkills: [],
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        let isPayment = JSON.parse(localStorage.getItem("isPayment"));
        if (isPayment) {
            window.location.href =
                "/invoice/" + isPayment.externalId + "/companyMember";
        }
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("company_name", this.form.companyName);
            formData.append("company_director", this.form.companyDirector);
            formData.append("company_address", this.form.companyAddress);
            formData.append("company_profile", this.form.companyProfile);
            formData.append("company_email", this.form.companyEmail);
            formData.append("phone", this.form.phone);
            formData.append("facsmile", this.form.facsmile);
            formData.append(
                "document_company_legality",
                this.form.documentCompanyLegality
            );
            formData.append(
                "document_company_logo",
                this.form.documentCompanyLogo
            );

            return formData;
        },
    },
    methods: {
        onBack() {
            this.$router.push({ name: "Member" });
        },
        handleSubmit() {
            this.isLoading = true;
            this.$store
                .dispatch("postDataUpload", [
                    "user/company-member",
                    this.formData,
                ])
                .then((response) => {
                    this.isLoading = false;
                    localStorage.setItem(
                        "isPayment",
                        JSON.stringify({
                            paymentType: "companyMember",
                            externalId: response.data.externalId,
                        })
                    );

                    window.location.href =
                        "/invoice/" +
                        response.data.externalId +
                        "/companyMember";
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
        uploadDocumentCompanyLegality(e) {
            this.form.documentCompanyLegality = e.target.files[0];
        },
        uploadDocumentCompanyLogo(e) {
            this.form.documentCompanyLogo = e.target.files[0];
        },
    },
    components: { PageTitle },
};
</script>

<template>
    <PageTitle title="Daftar Member Company" :isBack="true" @onBack="onBack" />

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label>Nama Perusahaan</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.companyName"
                        :class="{ 'is-invalid': errors.companyName }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.companyName"
                        v-for="(error, index) in errors.companyName"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Penanggung Jawab</label
                    ><input
                        type="text"
                        class="form-control"
                        v-model="form.companyDirector"
                        :class="{ 'is-invalid': errors.companyDirector }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.companyDirector"
                        v-for="(error, index) in errors.companyDirector"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Alamat Perusahaan</label
                    ><input
                        type="text"
                        class="form-control"
                        v-model="form.companyAddress"
                        :class="{ 'is-invalid': errors.companyAddress }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.companyAddress"
                        v-for="(error, index) in errors.companyAddress"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Profil Perusahaan</label>
                    <textarea
                        class="form-control"
                        v-model="form.companyProfile"
                        :class="{ 'is-invalid': errors.companyProfile }"
                        :disabled="isLoading"
                    ></textarea>
                    <div
                        class="invalid-feedback"
                        v-if="errors.companyProfile"
                        v-for="(error, index) in errors.companyProfile"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>No Telepon</label
                    ><input
                        type="text"
                        class="form-control"
                        v-model="form.phone"
                        :class="{ 'is-invalid': errors.phone }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.phone"
                        v-for="(error, index) in errors.phone"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>No Fax</label
                    ><input
                        type="text"
                        class="form-control"
                        v-model="form.facsmile"
                        :class="{ 'is-invalid': errors.facsmile }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.facsmile"
                        v-for="(error, index) in errors.facsmile"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Email Perusahaan</label
                    ><input
                        type="email"
                        class="form-control"
                        v-model="form.companyEmail"
                        :class="{ 'is-invalid': errors.companyEmail }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.companyEmail"
                        v-for="(error, index) in errors.companyEmail"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Legalitas Perusahaan</label
                    ><input
                        type="file"
                        class="form-control"
                        :class="{
                            'is-invalid': errors.documentCompanyLegality,
                        }"
                        :disabled="isLoading"
                        @change="uploadDocumentCompanyLegality"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentCompanyLegality"
                        v-for="(error, index) in errors.documentCompanyLegality"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-3">
                    <label>Logo Perusahaan</label
                    ><input
                        type="file"
                        class="form-control"
                        :class="{
                            'is-invalid': errors.documentCompanyLogo,
                        }"
                        :disabled="isLoading"
                        @change="uploadDocumentCompanyLogo"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentCompanyLogo"
                        v-for="(error, index) in errors.documentCompanyLogo"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
            </div>
            <div class="card-footer border-top d-flex justify-content-between">
                <router-link
                    :to="{ name: 'Member' }"
                    class="btn btn-sm btn-secondary"
                    :disabled="isLoading"
                    >Batal</router-link
                >
                <button class="btn btn-sm btn-primary" v-if="!isLoading">
                    Simpan
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
    </form>
</template>
