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
            if (roles) {
                roles.forEach((role) => {
                    if (this.roles && this.roles.includes(role)) {
                        return true;
                    }
                    return false;
                });
            }
        },
    },
    components: { Admin, Welder },
};
</script>

<template>
    <Admin v-if="$can('index-admin', 'Jobvacancy')" />
    <Welder
        v-if="
            $can('index-welder', 'Jobvacancy') &&
            checkRoleAccess([$store.state.MEMBER_WELDER, $store.state.GUEST])
        "
    />
</template>
