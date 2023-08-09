<script>
import Error from "../../components/notifications/Error.vue";
import Success from "../../components/notifications/Success.vue";

export default {
    data() {
        return {
            isLoading: false,
            msg: "",
            form: { email: "" },
            errors: {},
        };
    },
    methods: {
        handleSubmit() {
            this.errors = {};
            this.isLoading = true;

            this.$store
                .dispatch("postData", ["auth/forgot-password", this.form])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    if (error.response.data.messages) {
                        this.errors = error.response.data.messages;
                    } else {
                        $("#errorModal").modal("show");
                        this.msg = error.response.data.message;
                    }
                });
        },
    },

    components: {
        Success,
        Error,
    },
};
</script>

<template>
    <section>
        <div class="d-flex flex-wrap align-items-stretch">
            <div
                class="col-lg-8 col-12 order-lg-1 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
            >
                <div
                    class="position-absolute"
                    style="
                        height: 100%;
                        width: 100%;
                        background: linear-gradient(
                            0deg,
                            rgba(0, 0, 0, 0.875) 27%,
                            hsla(0, 0%, 100%, 0)
                        );
                    "
                ></div>
                <img
                    class=""
                    style="height: 100%; width: 100%"
                    src="../../../images/hero.png"
                    alt=""
                />
            </div>
            <div
                class="col-lg-4 col-md-6 col-12 order-lg-2 min-vh-100 order-2 bg-white"
            >
                <div class="p-4 m-3">
                    <center>
                        <img
                            src="https://www.api-iws.org/images/icon.jpg"
                            alt=""
                            style="max-height: 50px; margin-bottom: 20px"
                        />
                    </center>

                    <h3
                        class="text-primary font-weight-normal font-weight-bold text-center mb-3 text-uppercase"
                    >
                        ASOSIASI pengelasan INDONESIA
                    </h3>

                    <p class="text-muted mb-3">
                        Masukkan email Anda dan kami akan mengirimkan tautan
                        untuk masuk kembali ke akun Anda.
                    </p>

                    <form @submit.prevent="handleSubmit">
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                autofocus
                                class="form-control"
                                v-model="form.email"
                                :class="{ 'is-invalid': errors.email }"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.email"
                                v-for="(error, index) in errors.email"
                                :key="index"
                                v-html="error"
                            ></div>
                        </div>
                        <div class="mb-3">
                            <button
                                class="btn btn-primary form-control"
                                v-if="!isLoading"
                            >
                                Lanjutkan
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
                        <div class="mb-3 text-center">
                            <router-link :to="{ name: 'Login' }"
                                >Kembali ke halaman login</router-link
                            >.
                        </div>
                    </form>
                    <footer class="main-footer">
                        <div class="text-center mt-5 text-small">
                            Copyright &copy; 2023 Asosiasi Pengelasan Indonesia.
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </section>

    <Success
        :msg="'cek email anda untuk merubah password.'"
        :url="{ name: 'Login' }"
    />
    <Error :msg="msg" />
</template>

<style>
#auth .main-footer {
    padding: 0;
    margin-top: 0;
}
</style>
