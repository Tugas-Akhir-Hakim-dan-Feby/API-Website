<script>
import PageTitle from "../../components/PageTitle.vue";
import ChangePassword from "./profile/ChangePassword.vue";
import ProfileAdminHub from "./profile/ProfileAdminHub.vue";
import ProfileAdminApp from "./profile/ProfileAdminApp.vue";
import ProfileAdminBranch from "./profile/ProfileAdminBranch.vue";
import ProfileCompanyMember from "./profile/ProfileCompanyMember.vue";
import ProfileWelderMember from "./profile/ProfileWelderMember.vue";

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
        ProfileCompanyMember,
        ProfileWelderMember,
    },
};
</script>
<template>
    <ProfileAdminApp
        v-if="
            $can('update-admin-app', 'Profile') ||
            $can('update-guest', 'Profile')
        "
    />
    <ProfileAdminHub v-if="$can('update-admin-hub', 'Profile')" />
    <ProfileAdminBranch v-if="$can('update-admin-branch', 'Profile')" />
    <ProfileCompanyMember v-if="$can('update-company-member', 'Profile')" />
    <ProfileWelderMember v-if="$can('update-welder-member', 'Profile')" />
    <ChangePassword v-if="$can('update-password', 'Profile')" />
</template>
