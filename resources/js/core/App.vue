<script>
import Footer from "./components/Footer.vue";
import LeftSidebar from "./components/LeftSidebar.vue";
import Navbar from "./components/Navbar.vue";

export default {
    components: { LeftSidebar, Navbar, Footer },
};
</script>
<template>
    <div class="wrapper">
        <LeftSidebar />

        <div class="content-page">
            <div class="content">
                <Navbar />

                <div class="container-fluid">
                    <router-view></router-view>
                </div>
            </div>

            <Footer />
        </div>
    </div>
</template>
