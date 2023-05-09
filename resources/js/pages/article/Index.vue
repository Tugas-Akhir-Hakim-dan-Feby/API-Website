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
            if (data == this.roles) {
                return true;
            }
        },
    },
    components: { Admin, Guest },
};
</script>

<template>
    <div
        v-if="
            checkRoles($store.state.GUEST) ||
            checkRoles($store.state.MEMBER_WELDER) ||
            checkRoles($store.state.EXPERT)
        "
    >
        <Guest />
    </div>
    <div v-else-if="
            checkRoles($store.state.MEMBER_COMPANY) ||
            checkRoles($store.state.ADMIN_HUB) ||
            checkRoles($store.state.ADMIN_BRANCH) ||
            checkRoles($store.state.ADMIN_APP)
            ">
        <Admin />
    </div>
</template>
