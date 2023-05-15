<script>
import PageTitle from "../../components/PageTitle.vue";
import jsCookie from "js-cookie";

export default {
    data() {
        return {
            user: {},
            isWarning: false,
            isSuccess: false,
            isNotExpert: false,
        };
    },
    mounted() {
        this.getUser();
        setTimeout(() => {
            this.isNotExpert = jsCookie.get("isNotExpert");
        }, 1000);
    },
    methods: {
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.user = response.user;

                    if (
                        response.user.expert &&
                        !response.roles.includes(this.$store.state.EXPERT)
                    ) {
                        jsCookie.set("isNotExpert", true);
                        this.isWarning = true;
                    }

                    if (
                        response.user.expert &&
                        response.roles.includes(this.$store.state.EXPERT)
                    ) {
                        this.isSuccess = true;
                    }
                })
                .catch((error) => {});
        },
        removeTokenNotExpert() {
            jsCookie.remove("isNotExpert");
        },
    },
    components: { PageTitle },
};
</script>

<template>
    <PageTitle :title="'Dashboard'">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </PageTitle>

    <div
        class="alert alert-warning alert-dismissible"
        role="alert"
        v-if="isWarning && isNotExpert"
    >
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
        Data anda sebagai pakar sedang diperiksa, harap tunggu konfirmasi dari
        Admin!
    </div>

    <div
        class="alert alert-success alert-dismissible"
        role="alert"
        v-if="isSuccess && isNotExpert"
    >
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
            @click="removeTokenNotExpert"
        ></button>
        Selamat anda sudah terdaftar sebagai Pakar dari
        <strong>API-IWS</strong> !!!
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Branch' }"
                                ><div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <i
                                            class="ri-list-check-2 text-muted font-24"
                                        ></i>
                                        <h3 class="text-dark">
                                            <span>71</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Pengguna API Cabang
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Expert' }">
                                <div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <i
                                            class="ri-group-line text-muted font-24"
                                        ></i>
                                        <h3 class="text-dark">
                                            <span>31</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Pengguna Pakar
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Company' }">
                                <div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <h3 class="text-dark">
                                            <span>9</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Perusahaan Member
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Member' }">
                                <div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <h3 class="text-dark">
                                            <span>90</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Welder Member
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
