<script>
export default {
    methods: {
        handleSubmit() {
            window.location.href = "/";
        },
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
                    class="p-5"
                    style="height: 100%"
                    src="../../../images/api-iws.png"
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
                        Masukan email dan password anda, untuk mengakses halaman
                        selanjutnya.
                    </p>

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
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            autofocus
                            class="form-control"
                        />
                    </div>
                    <div class="mb-3">
                        <router-link to="/auth/forgot-password"
                            >Lupa password?</router-link
                        >
                    </div>
                    <div class="mb-3">
                        <button
                            @click="handleSubmit"
                            class="btn btn-primary btn-block"
                        >
                            Login
                        </button>
                    </div>
                    <hr />
                    <div class="mb-3 text-center">
                        <router-link to="/auth/register"
                            >Anda belum punya akun? silahkan daftar terlebih
                            dahulu.
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
</template>

<style>
#auth .main-footer {
    padding: 0;
    margin-top: 0;
}
</style>
