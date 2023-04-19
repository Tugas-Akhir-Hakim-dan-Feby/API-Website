<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
import Edit from "./Edit.vue";

export default {
    props: ["id"],
    data() {
        return {
            examPacket: {},
            errors: {},
            date: null,
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
                .dispatch("showData", ["exam-packet", this.id])
                .then((response) => {
                    this.isLoading = false;
                    this.examPacket = response.data;
                    Object.assign(this.examPacket, {
                        date: response.data.schedule.slice(0, 10),
                    });
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        onSuccessEdit() {
            this.getExamPacket();
            $("#successModal").modal("show");
        },
    },
    components: { PageTitle, Success, Edit },
};
</script>

<template>
    <PageTitle title="Detail Paket" :isBack="true" />

    <Edit :examPacket="examPacket" @onSuccessEdit="onSuccessEdit" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Daftar Pertanyaan</h4>
            <button class="btn btn-primary">Tambah Pertanyaan</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pertanyaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(exam, index) in examPacket.exams"
                            :key="index"
                        >
                            <th v-html="index + 1"></th>
                            <td v-html="exam.question"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Success :msg="'data berhasil diperbaharui.'" />
</template>
