<script>
import PageTitle from "../../components/PageTitle.vue";

export default {
    methods: {
        onBack(e) {
            this.$router.push({ name: "Exam Packet" });
        },
    },
    components: { PageTitle },
};
</script>

<template>
    <PageTitle
        title="Hasil Uji Kompetensi"
        :isBack="true"
        @onBack="onBack($event)"
    />
</template>
