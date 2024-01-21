<script>
import Admin from "./Admin.vue";
import Welder from "./Welder.vue";

export default {
    data() {
        return {
            roles: null,
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.roles = response.roles;
                })
                .catch((error) => {});
        },
        checkRoleAccess(roles) {
            let access = false;
            if (roles) {
                roles.forEach((role) => {
                    if (
                        this.roles &&
                        this.roles.includes(role) &&
                        this.$can("index-welder", "Jobvacancy")
                    ) {
                        access = true;
                    }
                });
            }
            return access;
        },
    },
    components: { Admin, Welder },
};
</script>

<template>
    <Admin
        v-if="
            $can('index-admin', 'Jobvacancy') &&
            ($store.state.USER.roleId == 5 ||
                $store.state.USER.roleId == 1 ||
                $store.state.USER.roleId == 2)
        "
    />
    <Welder
        v-if="
            checkRoleAccess([
                $store.state.MEMBER_WELDER,
                $store.state.GUEST,
                $store.state.EXPERT,
            ]) && $store.state.USER.roleId != 5
        "
    />
</template>
