<script>
import Success from "../../components/notifications/Success.vue";

export default {
    data() {
        return {
            password: "",
            confirmPassword: "",
            errors: {},
            isLoading: false,
            msg: "",
        };
    },
    methods: {
        handleSubmit() {
            this.errors = {};
            this.isLoading = true;

            this.$store
                .dispatch("postData", [
                    "auth/new-password",
                    {
                        token: this.$route.query.token,
                        email: this.$route.query.email,
                        password: this.password,
                        password_confirmation: this.confirmPassword,
                    },
                ])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                    this.msg =
                        "password anda berhasil diubah, silahkan login kembali.";
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
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4
                                    class="text-dark-50 text-center pb-0 fw-bold"
                                >
                                    Reset Password
                                </h4>
                            </div>

                            <form @submit.prevent="handleSubmit">
                                <div class="mb-3">
                                    <label for="password" class="form-label"
                                        >Password</label
                                    >
                                    <input
                                        class="form-control"
                                        type="password"
                                        id="password"
                                        v-model="password"
                                        :disabled="isLoading"
                                        :class="{
                                            'is-invalid': errors.password,
                                        }"
                                    />
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.password"
                                        v-for="(
                                            error, index
                                        ) in errors.password"
                                        :key="index"
                                        v-html="error"
                                    ></div>
                                </div>

                                <div class="mb-3">
                                    <label
                                        for="confirmPassword"
                                        class="form-label"
                                        >Konfirmasi Password</label
                                    >
                                    <input
                                        class="form-control"
                                        type="password"
                                        id="confirmPassword"
                                        v-model="confirmPassword"
                                        :disabled="isLoading"
                                        :class="{
                                            'is-invalid':
                                                errors.passwordConfirmation,
                                        }"
                                    />
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.passwordConfirmation"
                                        v-for="(
                                            error, index
                                        ) in errors.passwordConfirmation"
                                        :key="index"
                                        v-html="error"
                                    ></div>
                                </div>

                                <div class="mb-3 mb-0 text-center">
                                    <button
                                        class="btn btn-primary form-control"
                                        type="submit"
                                        v-if="!isLoading"
                                    >
                                        Submit
                                    </button>
                                    <button
                                        class="btn btn-primary form-control"
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
            </div>
        </div>
    </div>

    <Success :msg="msg" :url="{ name: 'Login' }" />
</template>
