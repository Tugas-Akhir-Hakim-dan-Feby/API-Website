<script>
import Success from "../../../components/notifications/Success.vue";
import Util from "../../../store/utils/util";

export default {
    data() {
        return {
            form: {
                skillName: "",
                skillDescription: "",
            },
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        Util.removeInvalidClass();
    },
    methods: {
        onCancel() {
            this.$emit("onCancel", true);
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("postData", ["skill/expert", this.form])
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
                    <label for="skillName">Nama Keahlian</label>
                    <input
                        type="text"
                        class="form-control form-validation"
                        id="skillName"
                        :class="{ 'is-invalid': errors.skillName }"
                        v-model="form.skillName"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.skillName"
                        v-for="(error, index) in errors.skillName"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label for="skillDescription">Deskripsi</label>
                    <textarea
                        class="form-control form-validation"
                        rows="5"
                        :class="{ 'is-invalid': errors.skillDescription }"
                        v-model="form.skillDescription"
                        :disabled="isLoading"
                    ></textarea>
                    <div
                        class="invalid-feedback"
                        v-if="errors.skillDescription"
                        v-for="(error, index) in errors.skillDescription"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button
                    class="btn btn-sm btn-secondary"
                    @click="onCancel"
                    :disabled="isLoading"
                >
                    Batal
                </button>
                <button class="btn btn-sm btn-success" v-if="!isLoading">
                    Simpan
                </button>
                <button
                    class="btn btn-sm btn-success"
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

    <Success :msg="'data berhasil ditambahkan.'" />
</template>
