<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import Success from "../../components/notifications/Success.vue";
import util from "../../store/utils/util";
import camelcaseKeys from "camelcase-keys";

export default {
    props: ["uuid"],
    data() {
        return {
            isDetail: false,
            isLoading: false,
            jobVacancy: {},
            detailWorker: {},
            registerJobs: [],
            metaPagination: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            village: "",
            district: "",
            regency: "",
            province: "",
            msg: "",
        };
    },
    watch: {
        uuid(newVal) {
            this.uuid = newVal;
        },
    },
    mounted() {
        this.getJobVacancy();
        this.getRegisterJobs();
    },
    methods: {
        getJobVacancy() {
            this.$store
                .dispatch("showData", ["job-vacancy", this.uuid])
                .then((response) => {
                    this.jobVacancy = response.data;
                });
        },
        getRegisterJobs() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
                `job_vacancy=${this.uuid}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["register-job", params])
                .then((response) => {
                    this.isLoading = false;
                    this.registerJobs = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        capitalize(string) {
            if (string) {
                return string.replace(/\b\w/g, (char) => char.toUpperCase());
            }
        },
        getCitizenship(citizenship) {
            return util.getCitizenship(citizenship);
        },
        getSkill(skill) {
            return util.convertToCapitalize(skill);
        },
        getStatus(status) {
            if (status == 1) {
                return "Diterima";
            }

            if (status == 2) {
                return "Ditolak";
            }

            return "Pending";
        },
        handleAccept(uuid) {
            this.isLoading = true;

            let body = {
                userId: uuid,
                jobVacancyId: this.uuid,
            };

            this.$store
                .dispatch("postData", ["register-job/accept", body])
                .then((response) => {
                    this.isLoading = false;
                    $("#detailWorker").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "lamaran berhasil diterima.";
                    this.getRegisterJobs();
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        handleReject(uuid) {
            this.isLoading = true;

            let body = {
                userId: uuid,
                jobVacancyId: this.uuid,
            };

            this.$store
                .dispatch("postData", ["register-job/reject", body])
                .then((response) => {
                    this.isLoading = false;
                    $("#detailWorker").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "lamaran berhasil ditolak.";
                    this.getRegisterJobs();
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        onBack() {
            this.$router.back();
        },
    },
    components: { PageTitle, Pagination, Loader, Success },
};
</script>

<template>
    <PageTitle title="Daftar Pelamar" :isBack="true" @onBack="onBack($event)">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Job Vacancy' }">
                    <span> Lowongan Pekerjaan </span>
                </router-link>
            </li>
            <li class="breadcrumb-item active">Daftar Pelamar</li>
        </ol>
    </PageTitle>

    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-primary">
                <div class="card-body profile-user-box">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div>
                                        <h4 class="mt-1 mb-1 text-white">
                                            {{
                                                jobVacancy.welderSkill
                                                    ?.skillName
                                            }}
                                        </h4>
                                        <p class="font-13 text-white-50">
                                            Lowongan Pekerjaan
                                        </p>

                                        <ul class="mb-0 list-inline text-light">
                                            <li class="list-inline-item me-3">
                                                <h5 class="mb-1 text-white">
                                                    {{ jobVacancy.workType }}
                                                </h5>
                                                <p
                                                    class="mb-0 font-13 text-white-50"
                                                >
                                                    Jenis Pekerjaan
                                                </p>
                                            </li>
                                            <li class="list-inline-item">
                                                <h5 class="mb-1 text-white">
                                                    {{ jobVacancy.placement }}
                                                </h5>
                                                <p
                                                    class="mb-0 font-13 text-white-50"
                                                >
                                                    Penempatan Kerja
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div
                                class="text-center mt-sm-0 mt-3 text-sm-end"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pelamar</th>
                            <th>Email Pelamar</th>
                            <th>No. Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(registerJob, index) in registerJobs"
                            :key="index"
                        >
                            <td>1</td>
                            <td>{{ registerJob.user?.name }}</td>
                            <td>{{ registerJob.user?.email }}</td>
                            <td>{{ registerJob.user?.personalData?.phone }}</td>
                            <td>
                                <span
                                    class="badge"
                                    :class="
                                        registerJob.status == 1
                                            ? 'btn-success'
                                            : registerJob.status == 2
                                            ? 'btn-danger'
                                            : 'btn-warning'
                                    "
                                    >{{ getStatus(registerJob.status) }}</span
                                >
                            </td>
                            <td>
                                <button
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailWorker"
                                    @click="
                                        (detailWorker = registerJob),
                                            (isDetail = true)
                                    "
                                >
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="detailWorker"
        tabindex="-1"
        aria-labelledby="detailWorkerLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detailWorkerLabel">
                        Detail Pelamar
                    </h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <center>
                                <img
                                    :src="
                                        detailWorker.user?.detailWorker
                                            ?.pasPhoto
                                    "
                                    :alt="detailWorker.user?.name"
                                    style="width: 3cm; height: 4cm"
                                />
                            </center>
                        </div>
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Nama
                                        </th>
                                        <td class="p-2 border">
                                            {{ detailWorker.user?.name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            No. KTP
                                        </th>
                                        <td class="p-2 border">
                                            {{
                                                detailWorker.user?.detailWorker
                                                    ?.residentIdCard
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Tempat, Tanggal Lahir
                                        </th>
                                        <td class="p-2 border">
                                            {{
                                                `${detailWorker.user?.detailWorker?.birthPlace}, ${detailWorker.user?.detailWorker?.dateBirth}`
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Alamat
                                        </th>
                                        <td class="p-2 border text-lowercase">
                                            {{
                                                `desa ${capitalize(
                                                    detailWorker.user
                                                        ?.personalData?.village
                                                        ?.name
                                                )}, kec. ${capitalize(
                                                    detailWorker.user
                                                        ?.personalData?.district
                                                        ?.name
                                                )}, kab. ${capitalize(
                                                    detailWorker.user
                                                        ?.personalData?.regency
                                                        ?.name
                                                )}, ${capitalize(
                                                    detailWorker.user
                                                        ?.personalData?.province
                                                        ?.name
                                                )}, ${
                                                    detailWorker.user
                                                        ?.personalData?.zipCode
                                                }`
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Kewarganegaraan
                                        </th>
                                        <td class="p-2 border">
                                            {{
                                                getCitizenship(
                                                    detailWorker.user
                                                        ?.personalData
                                                        ?.citizenship
                                                )
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Pengalaman Kerja
                                        </th>
                                        <td class="p-2 border">
                                            {{ detailWorker.experience }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Keahlian Pelamar
                                        </th>
                                        <td class="p-2 border">
                                            <ul>
                                                <li
                                                    v-for="(
                                                        welderSkill, index
                                                    ) in detailWorker.user
                                                        ?.welderHasSkills"
                                                    :key="index"
                                                >
                                                    {{
                                                        getSkill(
                                                            welderSkill
                                                                .welderSkill
                                                                ?.skillName
                                                        )
                                                    }}
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Jawaban Pelamar
                                            <br />
                                            <small class="p-0 fw-normal"
                                                >(terkait keunggulan
                                                pelamar)</small
                                            >
                                        </th>
                                        <td class="p-2 border">
                                            {{ detailWorker.promote }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 border bg-primary">
                                            Dokumen Pendukung
                                        </th>
                                        <td class="p-2 border">
                                            <ul>
                                                <li>
                                                    <a
                                                        :href="
                                                            detailWorker.user
                                                                ?.personalData
                                                                ?.curriculumVitae
                                                        "
                                                        target="_blank"
                                                        >CV</a
                                                    >
                                                </li>
                                                <li>
                                                    <a
                                                        :href="
                                                            detailWorker.policeRecord
                                                        "
                                                        target="_blank"
                                                        >SKCK</a
                                                    >
                                                </li>
                                                <li
                                                    v-for="(
                                                        certificate, index
                                                    ) in detailWorker.user
                                                        ?.welderDocuments"
                                                    :key="index"
                                                >
                                                    <a
                                                        :href="
                                                            certificate.documentPath
                                                        "
                                                        target="_blank"
                                                        >Sertifikat Keahlian
                                                        {{ index + 1 }}</a
                                                    >
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button
                        :disabled="isLoading"
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Kembali
                    </button>
                    <div
                        v-if="
                            detailWorker.status == 0 &&
                            $can('approval', 'Jobvacancy')
                        "
                    >
                        <button
                            :disabled="isLoading"
                            type="button"
                            class="btn btn-sm btn-danger me-2"
                            @click="handleReject(detailWorker.user.uuid)"
                        >
                            Ditolak
                        </button>
                        <button
                            :disabled="isLoading"
                            type="button"
                            class="btn btn-sm btn-success"
                            @click="handleAccept(detailWorker.user.uuid)"
                        >
                            Diterima
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Success :msg="msg" />
</template>

<style scoped>
th.bg-primary {
    background-color: #cfe2ff !important;
}
</style>
