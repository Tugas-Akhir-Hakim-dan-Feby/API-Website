<script>
import PageTitle from "../../components/PageTitle.vue";
import jsCookie from "js-cookie";
import Admin from "./Admin.vue";
import Expert from "./Expert.vue";
import WelderMember from "./WelderMember.vue";
import CompanyMember from "./CompanyMember.vue";

export default {
    data() {
        return {
            user: {},
            roles: {},
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
                    this.roles = response.roles;

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
        checkRole() {
            if (
                Array.isArray(this.roles) &&
                (this.roles.includes(this.$store.state.ADMIN_HUB) ||
                    this.roles.includes(this.$store.state.ADMIN_BRANCH) ||
                    this.roles.includes(this.$store.state.ADMIN_APP))
            ) {
                return true;
            }

            return false;
        },
    },
    components: { PageTitle, Admin, Expert, WelderMember, CompanyMember },
};
</script>

<template>
    <div class="row mt-4 mb-3" v-if="!checkRole()">
        <div class="col-12">
            <h4 class="p-0 m-0">
                <span
                    v-html="user.name"
                    class="text-uppercase text-dark fw-bold"
                    style="font-size: 20px"
                ></span>
            </h4>
            <p>{{ roles[0] }}</p>
        </div>
    </div>
    <div class="row mt-4 mb-3" v-if="checkRole()">
        <div class="col-12">
            <h4 class="p-0 m-0">Dashboard</h4>
        </div>
    </div>

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

    <WelderMember :user="user" v-if="!checkRole()" />

    <Admin v-if="checkRole()" />

    <Expert />

    <CompanyMember />
</template>
