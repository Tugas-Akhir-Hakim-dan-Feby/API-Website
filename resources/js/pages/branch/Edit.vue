<script>
import Success from "../../components/notifications/Success.vue";

export default {
    props: ["uuid"],
    data() {
        return {
            branch: {},
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        this.getBranch();
    },
    methods: {
        onCancel() {
            this.$emit("onCancel", true);
        },
        getBranch() {
            this.$store
                .dispatch("showData", ["branch", this.uuid])
                .then((response) => {
                    this.branch = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["branch", this.uuid, this.branch])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                    this.$emit("onCancel", true);
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
    },
    components: { Success },
};
</script>
<template>
    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <label for="branchName">Nama Cabang</label>
                    <input
                        type="text"
                        class="form-control"
                        id="branchName"
                        v-model="branch.branchName"
                        :class="{ 'is-invalid': errors.branchName }"
                        :disabled="isLoading"
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
                <div class="mb-2">
                    <label for="branchAddress">Alamat Cabang</label>
                    <input
                        type="text"
                        class="form-control"
                        id="branchAddress"
                        v-model="branch.branchAddress"
                        :disabled="isLoading"
                        :class="{ 'is-invalid': errors.branchAddress }"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.branchAddress"
                        v-for="(error, index) in errors.branchAddress"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label for="branchPhone">No. Telepon Cabang</label>
                    <input
                        type="text"
                        class="form-control"
                        id="branchPhone"
                        v-model="branch.branchPhone"
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
            <div class="card-footer d-flex justify-content-between">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="onCancel"
                    :disabled="isLoading"
                >
                    Batal
                </button>
                <button class="btn btn-success" v-if="!isLoading">
                    Simpan
                </button>
                <button
                    class="btn btn-success"
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

    <Success :msg="'data berhasil diperbaharui.'" />
</template>
