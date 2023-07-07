<script>
import Admin from "./Admin.vue";
import Welder from "./Welder.vue";

export default {
    components: { Admin, Welder },
};
</script>

<template>
    <Admin v-if="$can('index-admin', 'Jobvacancy')" />
    <Welder v-if="$can('index-welder', 'Jobvacancy')" />
</template>
