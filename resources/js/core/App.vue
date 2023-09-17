<script>
import Footer from "./components/Footer.vue";
import LeftSidebar from "./components/LeftSidebar.vue";
import Navbar from "./components/Navbar.vue";
import Cookie from "js-cookie";
import { mapMutations } from "vuex";
import { AbilityBuilder, Ability, defineAbility } from "@casl/ability";
import Error from "../components/notifications/Error.vue";

export default {
    data() {
        return {
            user: null,
            roles: {},
            errorMessage: null,
        };
    },
    computed: {},
    watch: {
        "$route.params.search": {
            handler: function (search) {
                this.$store
                    .dispatch("postData", ["/auth/check", {}])
                    .then((response) => {
                        this.user = response.user;
                        this.roles = response.roles;

                        var permission = response.permission;
                        const { can, rules } = new AbilityBuilder(Ability);
                        for (var prop in permission) {
                            if (permission.hasOwnProperty(prop)) {
                                can(
                                    permission[prop],
                                    prop.charAt(0).toUpperCase() + prop.slice(1)
                                );
                            }
                        }
                        this.$ability.update(rules);

                        this.$store.commit("setUser", response.user);
                        this.$store.commit(
                            "setPermission",
                            response.permission
                        );
                    })
                    .catch((error) => {
                        this.error = error.response.data;
                        Cookie.remove("token");
                        window.location.replace("/auth/login");
                    });
                if (!Cookie.get("token")) {
                    window.location.replace("/auth/login");
                }

                this.checkPayment();
            },
            deep: true,
            immediate: true,
        },
    },
    methods: {
        checkPayment() {
            this.$store
                .dispatch("showData", ["user", "check-payment"])
                .then((response) => {});
        },
        camelCase(string) {
            return string
                .toLowerCase()
                .split(" ")
                .map((word, index) => {
                    if (index === 0) {
                        return word;
                    }
                    return word.charAt(0).toUpperCase() + word.slice(1);
                })
                .join("");
        },
    },
    components: { LeftSidebar, Navbar, Footer, Error },
};
</script>
<template>
    <div class="wrapper">
        <LeftSidebar v-if="roles" :roles="roles" />

        <div class="content-page">
            <div class="content">
                <Navbar v-if="user" :user="user" :roles="roles" />

                <div class="container-fluid">
                    <router-view></router-view>
                </div>
            </div>

            <Footer />
            <Error :msg="errorMessage" />
        </div>
    </div>
</template>
