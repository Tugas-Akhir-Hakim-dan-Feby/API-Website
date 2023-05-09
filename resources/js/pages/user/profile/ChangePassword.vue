<script>
export default {
    data() {
        return {
            form: {
                currentPassword: "",
                newPassword: "",
                confirmPassword: "",
            },
            errors: {},
            isLoading: false,
            showAlert: false,
        };
    },
    methods: {
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("postData", ["user/update-password", this.form])
                .then((response) => {
                    this.isLoading = false;
                    this.showAlert = true;
                    this.form = {};
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
    },
};
</script>
<template>
    <div
        class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
        role="alert"
        v-if="typeof errors == 'string'"
    >
        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
        <strong>Galat - </strong> {{ errors }}
    </div>

    <div
        class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
        role="alert"
        v-if="showAlert"
    >
        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
        <strong>berhasil - </strong> password berhasil diperbaharui
    </div>

    <form @submit.prevent="handleSubmit" method="post">
        <div class="row mb-3">
            <label class="col-3 col-form-label">Email</label>
            <div class="col-9">
                <input
                    type="email"
                    class="form-control"
                    value="dominic.keller@mailinator.com"
                    disabled
                />
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-3 col-form-label">Password Saat Ini</label>
            <div class="col-9">
                <input
                    type="password"
                    class="form-control"
                    v-model="form.currentPassword"
                    :disabled="isLoading"
                    :class="{
                        'is-invalid': errors.currentPassword,
                    }"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.currentPassword"
                    v-for="(error, index) in errors.currentPassword"
                    :key="index"
                    v-html="error"
                ></div>
                <div
                    class="invalid-feedback"
                    v-if="typeof errors == 'string'"
                    v-html="errors"
                ></div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-3 col-form-label">Password Baru</label>
            <div class="col-9">
                <input
                    type="password"
                    class="form-control"
                    v-model="form.newPassword"
                    :disabled="isLoading"
                    :class="{ 'is-invalid': errors.newPassword }"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.newPassword"
                    v-for="(error, index) in errors.newPassword"
                    :key="index"
                    v-html="error"
                ></div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-3 col-form-label">Konfirmasi Password Baru</label>
            <div class="col-9">
                <input
                    type="password"
                    class="form-control"
                    v-model="form.confirmPassword"
                    :disabled="isLoading"
                    :class="{ 'is-invalid': errors.confirmPassword }"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.confirmPassword"
                    v-for="(error, index) in errors.confirmPassword"
                    :key="index"
                    v-html="error"
                ></div>
            </div>
        </div>
        <div>
            <button class="btn btn-success">Simpan</button>
        </div>
    </form>
</template>
