<script>
import Success from "../../components/notifications/Success.vue";

export default {
    data() {
        return {
            form: {
                name: "",
                email: "",
                password: "",
                retypePassword: "",
            },
            errors: {},
            isLoading: false,
            msg: "",
        };
    },
    mounted() {},
    methods: {
        handleSubmit() {
            this.errors = {};
            this.isLoading = true;

            this.$store
                .dispatch("postData", ["auth/register", this.form])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                    this.msg =
                        "Akun anda berhasil didaftarkan, silahkan cek email anda untuk melakukan aktivasi akun.";
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
    <section>
        <div class="d-flex flex-wrap align-items-stretch">
            <div
                class="col-lg-8 col-12 order-lg-1 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
            >
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
                        class="text-primary font-weight-normal text-uppercase font-weight-bold text-center mb-3"
                    >
                        ASOSIASI pengelasan INDONESIA
                    </h3>

                    <p class="text-muted mb-3">
                        Masukan nama dan email anda, untuk mendaftarkan akun
                        anda.
                    </p>

                    <form
                        @submit.prevent="handleSubmit"
                        class="needs-validation"
                        method="post"
                    >
                        <div class="mb-3">
                            <label for="name">Nama</label>
                            <input
                                type="text"
                                id="name"
                                autofocus
                                class="form-control"
                                v-model="form.name"
                                :class="{ 'is-invalid': errors.name }"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.name"
                                v-for="(error, index) in errors.name"
                                :key="index"
                                v-html="error"
                            ></div>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                v-model="form.email"
                                :disabled="isLoading"
                                :class="{ 'is-invalid': errors.email }"
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
                            <label for="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                autofocus
                                class="form-control"
                                v-model="form.password"
                                :class="{ 'is-invalid': errors.password }"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.password"
                                v-for="(error, index) in errors.password"
                                :key="index"
                                v-html="error"
                            ></div>
                        </div>

                        <div class="mb-3">
                            <label for="retypePassword">Ulangi Password</label>
                            <input
                                type="password"
                                id="retypePassword"
                                autofocus
                                class="form-control"
                                v-model="form.retypePassword"
                                :class="{ 'is-invalid': errors.retypePassword }"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.retypePassword"
                                v-for="(error, index) in errors.retypePassword"
                                :key="index"
                                v-html="error"
                            ></div>
                        </div>
                        <div class="mb-3">
                            <button
                                class="btn btn-primary form-control"
                                v-if="!isLoading"
                            >
                                Register
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

                    <hr />
                    <div class="mb-3 text-center">
                        <router-link to="/auth/login"
                            >Jika anda sudah punya akun, silahkan login.
                        </router-link>
                    </div>
                    <footer class="main-footer">
                        <div class="text-center mt-5 text-small">
                            Copyright &copy; 2023 Asosiasi Profesi Indonesia.
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </section>

    <Success :msg="msg" :url="{ name: 'Login' }" />
</template>

<style>
#auth .main-footer {
    padding: 0;
    margin-top: 0;
}
</style>
