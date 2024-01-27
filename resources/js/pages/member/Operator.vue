<script>
import PageTitle from "../../components/PageTitle.vue";

export default {
    data() {
        return {
            form: {
                tukName: "",
                tukType: "",
                tukCode: "",
                address: "",
                phone: "",
                facsmile: "",
                documentLogo: null,
            },
            errors: {},
            isLoading: false,
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("tuk_name", this.form.tukName);
            formData.append("tuk_type", this.form.tukType);
            formData.append("tuk_code", this.form.tukCode);
            formData.append("address", this.form.address);
            formData.append("phone", this.form.phone);
            formData.append("facsmile", this.form.facsmile);
            formData.append("document_logo", this.form.documentLogo);

            return formData;
        },
    },
    mounted() {
        let isPayment = JSON.parse(localStorage.getItem("isPayment"));
        if (isPayment) {
            window.location.href =
                "/invoice/" + isPayment.externalId + "/companyMember";
        }
    },
    methods: {
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};

            this.$store
                .dispatch("postDataUpload", ["user/operator", this.formData])
                .then((response) => {
                    this.isLoading = false;

                    localStorage.setItem(
                        "isPayment",
                        JSON.stringify({
                            paymentType: "tuk",
                            externalId: response.data.externalId,
                        })
                    );

                    window.location.href =
                        "/invoice/" + response.data.externalId + "/tuk";
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
        uploadDocumentLogo(e) {
            this.form.documentLogo = e.target.files[0];
        },
    },
    components: { PageTitle },
};
</script>

<template>
    <PageTitle
        :title="'Daftar Member TUK <span>(Tempat Uji Kompetensi)</span>'"
        :isBack="true"
        @onBack="onBack"
    />

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label>Nama TUK</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.tukName"
                        :class="{ 'is-invalid': errors.tukName }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.tukName"
                        v-for="(error, index) in errors.tukName"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Jenis TUK</label>
                    <select
                        class="form-select"
                        v-model="form.tukType"
                        :class="{ 'is-invalid': errors.tukType }"
                        :disabled="isLoading"
                    >
                        <option disabled selected></option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="Sewaktu">Sewaktu</option>
                        <option value="Jarak Jauh">Jarak Jauh</option>
                    </select>
                    <div
                        class="invalid-feedback"
                        v-if="errors.tukType"
                        v-for="(error, index) in errors.tukType"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Kode TUK</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.tukCode"
                        :class="{ 'is-invalid': errors.tukCode }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.tukCode"
                        v-for="(error, index) in errors.tukCode"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Alamat TUK</label>
                    <textarea
                        class="form-control"
                        v-model="form.address"
                        :class="{ 'is-invalid': errors.address }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.address"
                        v-for="(error, index) in errors.address"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>No Telepon</label>
                    <input
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
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>No Fax</label>
                    <input
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
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Logo TUK</label>
                    <input
                        type="file"
                        class="form-control"
                        @change="uploadDocumentLogo"
                        :class="{ 'is-invalid': errors.documentLogo }"
                        :disabled="isLoading"
                        accept="image/jpg, image/png, image/jpeg"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentLogo"
                        v-for="(error, index) in errors.documentLogo"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <router-link
                    :to="{ name: 'Member' }"
                    class="btn btn-sm btn-secondary"
                    >Batal</router-link
                >
                <button class="btn btn-sm btn-primary" v-if="!isLoading">
                    Daftar
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
