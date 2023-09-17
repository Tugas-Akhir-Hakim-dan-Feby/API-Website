<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";

import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/id";

export default {
    data() {
        return {
            isLoading: false,
            isRegistered: false,
            jobVacancies: [],
            pagination: {
                page: 1,
                perPage: 20,
            },
            filters: {
                search: "",
                skill: "",
                region: "",
                status: "",
            },
            metaPagination: {},
            welderSkills: [],
        };
    },
    mounted() {
        this.getWelderSkills();
        this.getJobVacancies();
    },
    methods: {
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", ""])
                .then((response) => {
                    this.welderSkills = response.data;
                });
        },
        getJobVacancies() {
            this.isLoading = true;

            let params = [
                `page=${this.pagination.page}`,
                `per_page=${this.pagination.perPage}`,
                `search=${this.filters.search}`,
                `skill=${this.filters.skill}`,
                `region=${this.filters.region}`,
                `status=${this.filters.status}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["job-vacancy", params])
                .then((response) => {
                    this.isLoading = false;
                    this.jobVacancies = response.data;
                    this.metaPagination = response.meta;
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
            date = dayjs(date).locale("id");
            return date.format("DD MMMM YYYY");
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
        onPageChange(e) {
            this.pagination.page = e;
            this.getJobVacancies();
        },
        onSearch() {
            setTimeout(() => {
                this.getJobVacancies();
            }, 1000);
        },
        onSearchSkill() {
            setTimeout(() => {
                this.getJobVacancies();
            }, 1000);
        },
        onSearchRegion() {
            setTimeout(() => {
                this.getJobVacancies();
            }, 1000);
        },
        onSearchStatus() {
            setTimeout(() => {
                this.getJobVacancies();
            }, 1000);
        },
    },
    components: { PageTitle, Loader, Pagination },
};
</script>

<template>
    <div class="d-md-flex align-items-center justify-content-between">
        <PageTitle title="Lowongan Pekerjaan" />

        <Pagination
            :pagination="metaPagination"
            @onPageChange="onPageChange($event)"
        />
    </div>

    <div class="row">
        <div class="col-lg-4 col-sm mb-3">
            <input
                type="search"
                class="form-control"
                placeholder="Jabatan / kata kunci / perusahaan"
                v-model="filters.search"
                @input="onSearch()"
            />
        </div>
        <div class="col-lg-4 col-sm mb-3">
            <input
                type="search"
                class="form-control"
                placeholder="Daerah / kota / kabupaten"
                v-model="filters.region"
                @input="onSearchRegion()"
            />
        </div>
        <div class="col-lg-4 col-sm mb-3">
            <select
                class="form-select"
                v-model="filters.skill"
                @change="onSearchSkill()"
            >
                <option selected disabled value="">
                    Spesialisasi pekerjaan
                </option>
                <option
                    v-for="(welderSkill, index) in welderSkills"
                    :key="index"
                    :value="welderSkill.uuid"
                    v-html="welderSkill.skillName"
                ></option>
            </select>
        </div>
    </div>

    <div class="alert alert-warning text-center" v-if="isLoading">
        harap tunggu...
    </div>
    <div
        class="alert alert-primary"
        v-if="jobVacancies.length < 1 && !isLoading"
    >
        lowongan pekerjaan belum tersedia...
    </div>

    <div class="row">
        <div
            class="col-lg-4 col-md-6"
            v-for="(jobVacancy, index) in jobVacancies"
            :key="index"
        >
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
                        <div>
                            <p
                                class="d-inline d-sm-none fw-bold"
                                v-html="getDeadline(jobVacancy.deadline)"
                            ></p>
                        </div>
                    </div>
                    <h4 class="d-none d-sm-block mt-3">
                        <router-link to="/">{{
                            jobVacancy.welderSkill?.skillName
                        }}</router-link>
                    </h4>
                    <div class="row mt-2">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <h4 class="d-block d-sm-none">
                                <router-link to="/">{{
                                    jobVacancy.welderSkill?.skillName
                                }}</router-link>
                                <small class="fw-normal d-inline d-sm-none"
                                    >&nbsp; ({{ jobVacancy.workType }})</small
                                >
                            </h4>
                            <p
                                class="mt-1"
                                v-html="jobVacancy.companyMember?.companyName"
                            ></p>
                            <h5><b v-html="jobVacancy.placement"></b></h5>
                            <p v-html="getCreatedAt(jobVacancy.createdAt)"></p>
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-sm-6 d-none d-sm-block"
                        >
                            <div>
                                <h5><b>Jenis Pekerjaan</b></h5>
                                <p v-html="jobVacancy.workType"></p>
                                <h5><b>Waktu Penutupan</b></h5>
                                <p
                                    v-html="getDeadline(jobVacancy.deadline)"
                                ></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <Pagination
            :pagination="metaPagination"
            @onPageChange="onPageChange($event)"
        />
    </div>
</template>

<style scoped>
.border-start {
    border-left: 5px solid #0d6efd !important;
    cursor: pointer;
}
</style>
