<script>
import PageTitle from "../../components/PageTitle.vue";
import dayjs from "dayjs";
import "dayjs/locale/id";
import Loader from "../../components/Loader.vue";

export default {
    props: ["examPacketId"],
    data() {
        return {
            examPacket: {},
            isLoading: false,
        };
    },
    mounted() {
        this.getExamPacket();
    },
    methods: {
        getExamPacket() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", [
                    "welder-answer/correct-answer",
                    this.examPacketId,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.examPacket = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getSchedule(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        onBack(e) {
            this.$router.push({ name: "Exam Packet" });
        },
    },
    components: { PageTitle, Loader },
};
</script>

<template>
    <PageTitle
        title="Hasil Uji Kompetensi"
        :isBack="true"
        @onBack="onBack($event)"
    />

    <div class="row position-relative">
        <Loader v-if="isLoading" />
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0">
                        Informasi Paket Uji Kompetensi
                    </h4>
                    <span
                        style="cursor: default"
                        class="btn mb-1 btn-sm btn-success"
                        v-if="examPacket.evaluation?.certificateNumber"
                        >KOMPETEN</span
                    >
                    <span
                        style="cursor: default"
                        class="btn mb-1 btn-sm btn-danger"
                        v-else-if="examPacket.evaluation?.grade"
                        >TIDAK KOMPETEN</span
                    >
                    <span
                        v-else
                        style="cursor: default"
                        class="btn mb-1 btn-sm btn-info"
                        >BELUM ADA PENILAIAN</span
                    >

                    <hr />

                    <div class="text-start">
                        <p class="text-muted">
                            <strong>Skema Uji Kompetensi :</strong>
                            <span
                                class="ms-2"
                                v-html="examPacket.competenceSchema?.skillName"
                            ></span>
                        </p>

                        <p
                            class="text-muted"
                            v-if="examPacket.evaluation?.certificateNumber"
                        >
                            <strong>Nomor Sertifikat :</strong>
                            <span
                                class="ms-2"
                                v-html="
                                    examPacket.evaluation?.certificateNumber
                                "
                            ></span>
                        </p>

                        <p class="text-muted">
                            <strong>Tanggal Ujian :</strong
                            ><span
                                class="ms-2"
                                v-html="getSchedule(examPacket.examSchedule)"
                            ></span>
                        </p>

                        <p class="text-muted">
                            <strong>Alamat Tempat Ujian :</strong>
                            <span
                                class="ms-2"
                                v-html="examPacket.operator?.address"
                            ></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th class="p-2 border">Aspek Penilaian</th>
                                <td
                                    class="p-2 border"
                                    v-html="examPacket.evaluation?.grade"
                                ></td>
                            </tr>
                            <tr>
                                <th class="p-2 border">Catatan</th>
                                <td
                                    class="p-2 border"
                                    v-html="
                                        examPacket.evaluation?.notes ??
                                        'Tidak Ada'
                                    "
                                ></td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <p class="mb-2">K = Kompeten</p>
                        <p class="">TK = Tidak Kompeten</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
