<script>
import Success from "../../components/notifications/Success.vue";
export default {
    methods: {
        handleSubmit() {
            $("#successModal").modal("show");
        },
    },

    components: {
        Success,
    },
};
</script>

<template>
    <section>
        <div class="d-flex flex-wrap align-items-stretch">
            <div
                class="col-lg-8 col-12 order-lg-1 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
            >
                <img
                    class="absolute-bottom-left index-2"
                    src="../../../images/api-iws.png"
                    style="height: 100%; width: 100%"
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
                            />
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary btn-block">
                                Lanjutkan
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
                            Copyright &copy; 2023 Asosiasi Profesi Indonesia.
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
</template>

<style>
#auth .main-footer {
    padding: 0;
    margin-top: 0;
}
</style>
