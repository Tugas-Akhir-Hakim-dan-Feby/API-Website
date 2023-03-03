<script>
import Footer from "./components/Footer.vue";
import LeftSidebar from "./components/LeftSidebar.vue";
import Navbar from "./components/Navbar.vue";
import Cookie from "js-cookie";

export default {
    data() {
        return {
            user: null,
        };
    },
    watch: {
        "$route.params.search": {
            handler: function (search) {
                this.$store
                    .dispatch("postData", ["/auth/check", {}])
                    .then((response) => {
                        this.user = response.user;
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
    components: { LeftSidebar, Navbar, Footer },
};
</script>
<template>
    <div class="wrapper">
        <LeftSidebar />

        <div class="content-page">
            <div class="content">
                <Navbar :user="user" />

                <div class="container-fluid">
                    <router-view></router-view>
                </div>
            </div>

            <Footer />
        </div>
    </div>
</template>
