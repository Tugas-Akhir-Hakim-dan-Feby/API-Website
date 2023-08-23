<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";

import Util from "../../store/utils/util";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/id";

export default {
    props: ["slug"],
    data() {
        return {
            isLoading: false,
            isRegistered: false,
            jobVacancy: {},
        };
    },
    mounted() {
        this.getJobVacancy();
    },
    methods: {
        getJobVacancy() {
            this.isLoading = true;

            this.$store
                .dispatch("showData", ["job-vacancy/show-by-slug", this.slug])
                .then((response) => {
                    this.isLoading = false;
                    this.jobVacancy = response.data;
                    this.checkJobRegister(response.data.uuid);
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getCreatedAt(date) {
            date = dayjs(date).locale("id");
            return date.format("DD MMMM YYYY");
        },
        getRupiah(amount) {
            return Util.getRupiah(amount);
        },
        getDeadline(date) {
            dayjs.extend(relativeTime);
            date = dayjs(date).locale("id").toNow();
            return date + " lagi";
        },
        checkJobRegister(jobVacancyId) {
            this.$store
                .dispatch("showData", ["register-job/check", jobVacancyId])
                .then((response) => {
                    this.isRegistered = response;
                });

            return this.isRegistered;
        },
        onBack() {
            this.$router.push({ name: "Job Vacancy" });
        },
    },
    components: { PageTitle, Loader },
};
</script>

<template>
    <PageTitle title="Detail Lowongan" :isBack="true" @onBack="onBack" />

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <img
            :src="jobVacancy.companyMember?.companyLogo"
            class="card-img-top"
            :alt="jobVacancy.companyMember?.companyName"
            style="max-height: 350px; width: 100%"
        />
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 v-html="jobVacancy.welderSkill?.skillName"></h4>
                    <h5 v-html="jobVacancy.companyMember?.companyName"></h5>
                    <p v-html="jobVacancy.placement"></p>
                    <p>
                        Ditayangkan pada
                        {{ getCreatedAt(jobVacancy.createdAt) }}
                    </p>
                </div>
                <div>
                    <router-link
                        :to="{
                            name: 'Job Vacancy Apply',
                            params: { slug: slug },
                        }"
                        class="btn btn-sm btn-success"
                        v-if="!isRegistered"
                        >Lamar Sekarang</router-link
                    >
                    <p
                        class="btn btn-sm btn-primary"
                        v-else-if="isRegistered"
                        style="cursor: default"
                    >
                        SUDAH TERDAFTAR
                    </p>
                </div>
            </div>
            <hr />
            <div>
                <h5 class="my-3">Deskripsi Pekerjaan</h5>
                <div v-html="jobVacancy.description"></div>
            </div>
            <div>
                <h5 class="mt-3">Informasi Tambahan</h5>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="fw-bold">Perkiraan Gaji</label>
                        <p
                            v-html="'&plusmn; ' + getRupiah(jobVacancy.salary)"
                        ></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="fw-bold">Jenis Pekerjaan</label>
                        <p v-html="jobVacancy.workType"></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="fw-bold">Waktu Penutupan</label>
                        <p v-html="getDeadline(jobVacancy.deadline)"></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <label class="fw-bold">Kontak Yang Dihubungi</label>
                        <p
                            v-html="
                                `${jobVacancy.contact} (${
                                    jobVacancy.contactName ?? 'Admin Perusahaan'
                                })`
                            "
                        ></p>
                    </div>
                </div>
            </div>
            <hr />
            <div>
                <h5 class="my-3">Tentang Perusahaan</h5>
                <div v-html="jobVacancy.companyMember?.companyProfile"></div>
            </div>
            <hr />
        </div>
    </div>
</template>
