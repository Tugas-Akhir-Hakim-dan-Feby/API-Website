<script>
import Footer from "./components/Footer.vue";
import LeftSidebar from "./components/LeftSidebar.vue";
import Navbar from "./components/Navbar.vue";
import Cookie from "js-cookie";
import { mapMutations } from "vuex";

export default {
    data() {
        return {
            user: null,
            roles: {},
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
                        this.$store.commit("setUser", response.user);
                    })
                    .catch((error) => {
                        this.error = error.response.data;
                        Cookie.remove("token");
                    });
                if (!Cookie.get("token")) {
                    window.location.replace("/auth/login");
                }
            },
            deep: true,
            immediate: true,
        },
    },
    methods: {
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
    components: { LeftSidebar, Navbar, Footer },
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
        </div>
    </div>
</template>
