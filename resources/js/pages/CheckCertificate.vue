<script>
import dayjs from "dayjs";
import "dayjs/locale/id";
import PageTitle from "../components/PageTitle.vue";
import QrcodeVue from "qrcode.vue";

export default {
    data() {
        return {
            isLoading: false,
            filters: {
                search: "",
                page: 1,
                perPage: 12,
            },
            qrcode: {
                value:
                    window.location.origin +
                    "/certificate_check/?certificate_number=",
                size: 220,
            },
            certificates: [],
            expert: {},
            metaPagination: {},
        };
    },
    mounted() {
        this.getCertificate();
    },
    methods: {
        getCertificate() {
            this.isLoading = true;

            if (this.$route.query.certificate_number) {
                this.filters.search = this.$route.query.certificate_number;
            }

            const params = [
                `per_page=${this.filters.perPage}`,
                `page=${this.filters.page}`,
                `search=${this.filters.search}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["user-exam-packet/all", params])
                .then((response) => {
                    this.isLoading = false;
                    this.certificates = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        onSearch() {
            setTimeout(() => {
                this.getCertificate();
            }, 1000);
        },
        onPageChange(e) {
            this.filters.page = e;
            this.getCertificate();
        },
        setExpert(expert) {
            this.expert = expert;
        },
        handleErrorImage(event) {
            event.target.src = null;
            event.target.src = ProfileNotFound;
        },
        getSchedule(schedule) {
            schedule = dayjs(schedule).locale("id").format("DD MMMM YYYY");

            return schedule;
        },
    },
    components: { PageTitle, QrcodeVue },
};
</script>

<template>
    <div class="row">
        <div class="col-md-7 col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">Cek Sertifikat</h4>
            </div>
        </div>
        <div class="col-md-5 col-sm-6">
            <div class="mt-sm-3 mt-0">
                <input
                    type="search"
                    class="form-control"
                    placeholder="Masukan no. Sertifikat"
                    v-model="filters.search"
                    @input="onSearch"
                />
            </div>
        </div>
    </div>

    <div class="card" v-if="!filters.search">
        <div class="card-body">
            <div class="d-flex justify-content-center align-items-center">
                Silahkan lakukan pencarian.
            </div>
        </div>
    </div>
    <div v-else>
        <div class="alert alert-warning" v-if="certificates.length < 1">
            Data tidak ditemukan.
        </div>
        <div
            v-else
            class="card"
            v-for="(certificate, index) in certificates"
            :key="index"
        >
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <center>
                            <qrcode-vue
                                class="mb-3"
                                :value="certificate.certificateNumber"
                                :size="qrcode.size"
                                level="H"
                            />
                        </center>
                    </div>
                    <div class="col-lg-9">
                        <div
                            class="d-flex justify-content-center align-items-center"
                        >
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th class="bg-primary border p-2">
                                        No. Sertifikat
                                    </th>
                                    <td class="border p-2">
                                        {{ certificate.certificateNumber }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-primary border p-2">
                                        Skema Uji Kompetensi
                                    </th>
                                    <td class="border p-2">
                                        {{
                                            certificate.examPacket
                                                ?.competenceSchema?.skillName
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-primary border p-2">
                                        Nama Lengkap
                                    </th>
                                    <td class="border p-2">
                                        {{ certificate.user?.name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-primary border p-2">Email</th>
                                    <td class="border p-2">
                                        {{ certificate.user?.email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-primary border p-2">
                                        Tanggal Uji Kompetensi
                                    </th>
                                    <td class="border p-2">
                                        {{
                                            getSchedule(
                                                certificate.examPacket
                                                    ?.examSchedule
                                            )
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-primary border p-2">
                                        Tempat Penyelenggara
                                    </th>
                                    <td class="border p-2">
                                        {{
                                            certificate.examPacket?.operator
                                                ?.address
                                        }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-primary {
    background-color: #cfe2ff !important;
}
</style>
