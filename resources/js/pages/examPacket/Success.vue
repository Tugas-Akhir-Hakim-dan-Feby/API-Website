<script>
import PageTitle from "../../components/PageTitle.vue";
import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    props: ["examPacketId"],
    data() {
        return {
            examPacket: {},
        };
    },
    mounted() {
        this.getExamPacket();
    },
    methods: {
        getExamPacket() {
            this.$store
                .dispatch("showData", [
                    "welder-answer/correct-answer",
                    this.examPacketId,
                ])
                .then((response) => {
                    this.examPacket = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getSchedule(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
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

    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0 mb-3">
                        Informasi Paket Uji Kompetensi
                    </h4>

                    <hr />

                    <div class="text-start">
                        <p class="text-muted">
                            <strong>Nama Paket :</strong>
                            <span class="ms-2" v-html="examPacket.name"></span>
                        </p>

                        <p class="text-muted">
                            <strong>Tanggal Ujian :</strong
                            ><span
                                class="ms-2"
                                v-html="getSchedule(examPacket.schedule)"
                            ></span>
                        </p>

                        <p class="text-muted">
                            <strong>Alamat Tempat Ujian :</strong>
                            <span
                                class="ms-2"
                                v-html="examPacket.practiceExamAddress"
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
                            <thead>
                                <tr>
                                    <th>Rincian</th>
                                    <th>Tanggal</th>
                                    <th>Presentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ujian Teori</td>
                                    <td
                                        v-html="
                                            getSchedule(examPacket.schedule)
                                        "
                                    ></td>
                                    <td
                                        v-html="
                                            examPacket.correctPrecentage
                                                ? `${examPacket.correctPrecentage} %`
                                                : '0 %'
                                        "
                                    ></td>
                                </tr>
                                <tr>
                                    <td>Ujian Praktek</td>
                                    <td
                                        v-html="
                                            getSchedule(examPacket.schedule)
                                        "
                                    ></td>
                                    <td
                                        v-html="
                                            examPacket.practiceValue
                                                ? `${examPacket.practiceValue} %`
                                                : `0 %`
                                        "
                                    ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
