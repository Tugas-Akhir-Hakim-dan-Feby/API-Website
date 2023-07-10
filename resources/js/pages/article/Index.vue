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
                .catch((error) => {});
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
    <div v-if="$can('index-guest', 'Article')">
        <Guest />
    </div>
    <div v-else>
        <Admin />
    </div>
</template>
