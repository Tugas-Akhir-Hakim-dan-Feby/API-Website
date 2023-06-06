<script>
import PageTitle from "../../components/PageTitle.vue";
import ChangePassword from "./profile/ChangePassword.vue";
import ProfileAdminHub from "./profile/ProfileAdminHub.vue";
import ProfileAdminApp from "./profile/ProfileAdminApp.vue";
import ProfileAdminBranch from "./profile/ProfileAdminBranch.vue";

export default {
    methods: {
        checkRoles(role) {
            let roles = this.$store.state.USER.roles.filter(
                (item) => item.name == role
            );
            if (roles.length > 0) {
                return true;
            }
            return false;
        },
    },
    components: {
        PageTitle,
        ProfileAdminHub,
        ChangePassword,
        ProfileAdminApp,
        ProfileAdminBranch,
    },
};
</script>
<template>
    <ProfileAdminApp v-if="checkRoles($store.state.ADMIN_APP)" />
    <ProfileAdminHub v-if="checkRoles($store.state.ADMIN_HUB)" />
    <ProfileAdminBranch v-if="checkRoles($store.state.ADMIN_BRANCH)" />
    <ChangePassword />
</template>
