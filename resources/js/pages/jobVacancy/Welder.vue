<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";

import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/id";

export default {
    data() {
        return {
            isLoading: false,
            jobVacancies: [],
        };
    },
    mounted() {
        this.getJobVacancies();
    },
    methods: {
        getJobVacancies() {
            this.isLoading = true;
            this.$store
                .dispatch("getData", ["job-vacancy", ""])
                .then((response) => {
                    this.isLoading = false;
                    this.jobVacancies = response.data;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getCreatedAt(date) {
            dayjs.extend(relativeTime);
            date = dayjs(date).locale("id");
            return date.fromNow();
        },
        getDeadline(date) {
            dayjs.extend(relativeTime);
            date = dayjs(date).locale("id").toNow();
            return date + " lagi";
        },
        getCloseDeadline(date) {
            date = dayjs(date).locale("id");
            if (date.isSame(dayjs(), "day")) {
                return false;
            }
            return true;
        },
        redirectTo(page) {
            this.$router.push(page);
        },
    },
    components: { PageTitle, Loader },
};
</script>

<template>
    <PageTitle title="Lowongan Pekerjaan"> </PageTitle>

    <div class="alert alert-warning text-center" v-if="isLoading">
        harap tunggu...
    </div>
    <div v-for="(jobVacancy, index) in jobVacancies" :key="index">
        <div
            class="card border-start"
            v-if="getCloseDeadline(jobVacancy.deadline)"
            @click="
                redirectTo({
                    name: 'Job Vacancy Detail',
                    params: { slug: jobVacancy.slug },
                })
            "
        >
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <img
                        :src="jobVacancy.companyMember?.companyLogo"
                        :alt="jobVacancy.companyMember?.companyName"
                        style="max-width: 300px; max-height: 75px"
                    />
                    <p
                        class="d-inline d-sm-none fw-bold"
                        v-html="getDeadline(jobVacancy.deadline)"
                    ></p>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <h4>
                            <router-link to="/">{{
                                jobVacancy.welderSkill?.skillName
                            }}</router-link>
                            <small class="fw-normal d-inline d-sm-none"
                                >&nbsp; ({{ jobVacancy.workType }})</small
                            >
                        </h4>
                        <p v-html="jobVacancy.companyMember?.companyName"></p>
                        <h5><b v-html="jobVacancy.placement"></b></h5>
                        <p v-html="getCreatedAt(jobVacancy.createdAt)"></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 d-none d-sm-block">
                        <div>
                            <h5><b>Jenis Pekerjaan</b></h5>
                            <p v-html="jobVacancy.workType"></p>
                            <h5><b>Waktu Penutupan</b></h5>
                            <p v-html="getDeadline(jobVacancy.deadline)"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.border-start {
    border-left: 5px solid #0d6efd !important;
    cursor: pointer;
}
</style>
