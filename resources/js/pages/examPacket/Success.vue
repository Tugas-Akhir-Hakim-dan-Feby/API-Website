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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Presentase</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td v-html="getSchedule(examPacket.schedule)"></td>
                            <td
                                v-html="examPacket.correctPrecentage + '%'"
                            ></td>
                            <td>
                                <span
                                    class="badge bg-success"
                                    v-if="examPacket.status == 'LULUS'"
                                    >{{ examPacket.status }}</span
                                >
                                <span
                                    class="badge bg-danger"
                                    v-else="examPacket.status == 'TIDAK LULUS'"
                                    >{{ examPacket.status }}</span
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <router-link
                    v-if="examPacket.status == 'LULUS'"
                    to="ok"
                    class="btn btn-sm btn-dark float-end"
                    >Cetak Sertifikat</router-link
                >
            </div>
        </div>
    </div>
</template>
