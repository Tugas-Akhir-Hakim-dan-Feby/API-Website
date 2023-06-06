<script>
import iziToast from "izitoast";

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
                    this.form = {};
                    iziToast.success({
                        title: "Selamat",
                        message: "data anda berhasil diperbaharui",
                        position: "topCenter",
                    });
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
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>Perbaharui Password</h4>
            <p>
                Pastikan akun Anda menggunakan password acak yang panjang agar
                tetap aman.
            </p>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <form @submit.prevent="handleSubmit" method="post">
                    <div class="card-body">
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

                        <div class="row mb-3">
                            <label class="col-3 col-form-label"
                                >Password Saat Ini</label
                            >
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
                                    v-for="(
                                        error, index
                                    ) in errors.currentPassword"
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
                            <label class="col-3 col-form-label"
                                >Password Baru</label
                            >
                            <div class="col-9">
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="form.newPassword"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.newPassword,
                                    }"
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
                            <label class="col-3 col-form-label"
                                >Konfirmasi Password Baru</label
                            >
                            <div class="col-9">
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="form.confirmPassword"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.confirmPassword,
                                    }"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.confirmPassword"
                                    v-for="(
                                        error, index
                                    ) in errors.confirmPassword"
                                    :key="index"
                                    v-html="error"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button
                            class="btn btn-sm btn-success"
                            v-if="!isLoading"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-success btn-sm"
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
            </div>
        </div>
    </div>
</template>
