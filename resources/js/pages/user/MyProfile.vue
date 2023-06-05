<script>
import PageTitle from "../../components/PageTitle.vue";
import ChangePassword from "./profile/ChangePassword.vue";
import ProfileAdmin from "./profile/ProfileAdmin.vue";
import ProfileAdminApp from "./profile/ProfileAdminApp.vue";

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
    components: { PageTitle, ProfileAdmin, ChangePassword, ProfileAdminApp },
};
</script>
<template>
    <ProfileAdminApp v-if="checkRoles($store.state.ADMIN_APP)" />
    <ProfileAdmin
        v-if="
            checkRoles($store.state.ADMIN_HUB) ||
            checkRoles($store.state.ADMIN_BRANCH)
        "
    />
    <ChangePassword />
</template>
