<script>
import role from "../../store/utils/role";
import Admin from "./Admin.vue";
import Guest from "./Guest.vue";

export default {
    data() {
        return {
            roles: [],
        };
    },
    mounted() {
        this.getProfile();
    },
    methods: {
        getProfile() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.roles = response.roles;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        checkRoles(data) {
            return role.checkRoleObject(this.roles, data);
        },
    },
    components: { Admin, Guest },
};
</script>

<template>
    <div
        v-if="
            checkRoles([
                $store.state.ADMIN_APP,
                $store.state.ADMIN_HUB,
                $store.state.ADMIN_BRANCH,
            ])
        "
    >
        <Admin />
    </div>
    <div v-else>
        <Guest />
    </div>
</template>
