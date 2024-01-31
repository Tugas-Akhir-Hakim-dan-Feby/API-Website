<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";

export default {
    data() {
        return {
            title: "Data Cabang",
            isLoading: false,
            errors: {},
            form: {
                branchName: "",
                branchPhone: "",
                branchAddress: "",
                branchStructure: null,
            },
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("branch_name", this.form.branchName);
            formData.append("branch_phone", this.form.branchPhone);
            formData.append("branch_address", this.form.branchAddress);
            if (this.form.branchStructure) {
                formData.append("file", this.form.branchStructure);
            }
            formData.append("_method", "put");

            return formData;
        },
    },
    mounted() {
        this.setForm();
    },
    methods: {
        setForm() {
            this.form.branchName =
                this.$store.state.USER.adminBranch?.branch?.branchName;
            this.form.branchPhone =
                this.$store.state.USER.adminBranch?.branch?.branchPhone;
            this.form.branchAddress =
                this.$store.state.USER.adminBranch?.branch?.branchAddress;
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("postDataUpload", [
                    "branch/" +
                        this.$store.state.USER.adminBranch?.branch?.uuid,
                    this.formData,
                ])
                .then((response) => {
                    this.isLoading = false;
                    window.location.reload();
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        uploadFile(e) {
            this.form.branchStructure = e.target.files[0];
        },
    },
    components: { PageTitle, Success },
};
</script>

<template>
    <PageTitle :title="title">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Data Cabang</li>
        </ol>
    </PageTitle>

    <form @submit.prevent="handleSubmit" method="post" class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label>Nama Cabang</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.branchName"
                        :disabled="isLoading"
                        :class="{ 'is-invalid': errors.branchName }"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.branchName"
                        v-for="(error, index) in errors.branchName"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <label>Telepon Cabang</label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="form.branchPhone"
                        :disabled="isLoading"
                        :class="{ 'is-invalid': errors.branchPhone }"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.branchPhone"
                        v-for="(error, index) in errors.branchPhone"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label>Alamat Cabang</label>
                <textarea
                    class="form-control"
                    v-model="form.branchAddress"
                    :disabled="isLoading"
                    :class="{ 'is-invalid': errors.branchAddress }"
                    rows="3"
                ></textarea>
                <div
                    class="invalid-feedback"
                    v-if="errors.branchAddress"
                    v-for="(error, index) in errors.branchAddress"
                    :key="index"
                >
                    {{ error }}.
                </div>
            </div>
            <div class="mb-3">
                <label>Unggah Struktur Organisasi</label>
                <input
                    type="file"
                    class="form-control"
                    @change="uploadFile($event)"
                    :disabled="isLoading"
                    :class="{ 'is-invalid': errors.file }"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.file"
                    v-for="(error, index) in errors.file"
                    :key="index"
                >
                    {{ error }}.
                </div>
            </div>

            <img
                v-if="$store.state.USER.adminBranch?.branch?.branchStructure"
                :src="$store.state.USER.adminBranch?.branch?.branchStructure"
                style="background-size: cover; min-width: 100%; height: 500px"
            />
        </div>
        <div class="card-footer">
            <button class="btn btn-sm btn-primary float-end" v-if="!isLoading">
                Simpan
            </button>
            <button
                class="btn btn-sm btn-primary float-end"
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
    </form>
    <Success :msg="'data berhasil diperbaharui.'" />
</template>
