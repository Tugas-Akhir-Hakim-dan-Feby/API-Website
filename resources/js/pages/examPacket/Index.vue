<script>
import Admin from "./Admin.vue";
import Welder from "./Welder.vue";
export default {
    components: { Admin, Welder },
};
</script>
<template>
    <Admin v-if="$can('dashboard-admin', 'Exampacket')" />
    <Welder v-else-if="$can('dashboard-member', 'Exampacket')" />
</template>
