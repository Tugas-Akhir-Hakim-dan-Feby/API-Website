<script>
import Loader from "../../../components/Loader.vue";
import iziToast from "izitoast";

export default {
    data() {
        return {
            uuid: null,
            isLoading: false,
            currentTime: null,
            examPacketId: null,
            examId: null,
            exams: {},
            examAnswer: {},
            examPacket: {},
            metaPagination: {},
            pagination: {
                perPage: 1,
                page: 1,
            },
            participantAnswer: "",
            numbering: [],
        };
    },
    beforeMount() {
        setTimeout(() => {
            this.examId = this.$route.params.examId;
            this.examPacketId = this.$route.params.examPacketId;
            this.getExams();
            this.getExamPacket();
        }, 1000);
        setTimeout(() => {
            this.getMinute(this.examPacket.startTime, this.examPacket.endTime);
        }, 2000);
    },
    mounted() {
        this.numbering = localStorage.getItem("numbering");
    },
    watch: {
        currentTime: {
            handler(value) {
                if (value > 0) {
                    setTimeout(() => {
                        this.currentTime--;
                    }, 1000);
                }
            },
            immediate: true,
        },

        uuid(newUuid) {
            if (newUuid) {
                return newUuid;
            }
        },
    },
    methods: {
        getExamPacket() {
            this.$store
                .dispatch("showData", ["exam-packet", this.examPacketId])
                .then((response) => {
                    this.examPacket = response.data;
                })
                .catch((err) => {});
        },
        getExams() {
            this.isLoading = true;

            let page = this.pagination.page;

            if (localStorage.getItem("pageStory")) {
                page = localStorage.getItem("pageStory");
            } else {
                page = this.pagination.page;
            }

            let params = [
                `exam_packet_id=${this.examPacketId}`,
                `per_page=${this.pagination.perPage}`,
                `page=${page}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["exam", params])
                .then((response) => {
                    this.isLoading = false;
                    this.exams = response.data;
                    this.metaPagination = response.meta;
                    this.getExam(response.data[0].uuid);
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getExam(examUuid) {
            this.$store
                .dispatch("showData", ["exam", examUuid])
                .then((response) => {
                    this.isLoading = false;
                    this.examAnswer = response.data;

                    if (response.data.welderAnswer) {
                        this.participantAnswer =
                            response.data.welderAnswer?.answer.uuid;
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getMinute(startTime, endTime) {
            const [startHours, startMinutes] = startTime.split(":");
            const [endHours, endMinutes] = endTime.split(":");

            const startDate = new Date();
            startDate.setHours(startHours);
            startDate.setMinutes(startMinutes);

            const endDate = new Date();
            endDate.setHours(endHours);
            endDate.setMinutes(endMinutes);

            let diff = (endDate.getTime() - startDate.getTime()) / 1000 / 60;
            if (diff < 0) {
                diff += 1440;
            }

            this.currentTime = diff.toFixed(0);
        },
        onPageChange(page, isSubmit) {
            this.pagination.page = page;

            localStorage.setItem("pageStory", page);

            if (isSubmit) {
                this.handleSubmit();
            } else {
                this.getExams();
            }
        },
        handleSubmit() {
            this.isLoading = true;

            let formData = {
                answerId: this.participantAnswer,
                examId: this.exams[0].uuid,
            };

            this.$store
                .dispatch("postData", ["welder-answer", formData])
                .then((response) => {
                    this.isLoading = false;
                    this.participantAnswer = null;
                    localStorage.setItem(
                        "numbering",
                        this.metaPagination.currentPage
                    );
                    this.getExams();
                })
                .catch((err) => {
                    this.isLoading = false;
                    iziToast.error({
                        message: "jawaban harus diisi!",
                        position: "topCenter",
                    });
                });
        },
        handleFinish() {
            this.isLoading = true;

            let formData = {
                answerId: this.participantAnswer,
                examId: this.exams[0].uuid,
            };

            this.$store
                .dispatch("postData", ["welder-answer", formData])
                .then((response) => {
                    this.isLoading = false;
                    this.participantAnswer = null;
                    $("#confirmModal").modal("hide");
                    window.location.href = `/exam-packet/${this.examPacketId}/success`;
                })
                .catch((err) => {
                    this.isLoading = false;
                    iziToast.error({
                        message: "jawaban harus diisi!",
                        position: "topCenter",
                    });
                });
        },
    },
    components: { Loader },
};
</script>
<template>
    <div class="wrapper">
        <Loader v-if="isLoading" />
        <div class="leftside-menu">
            <div class="h-100" id="leftside-menu-container" data-simplebar="">
                <ul>
                    <li
                        v-for="(item, index) in metaPagination.total"
                        :key="index"
                        :class="{
                            active: metaPagination.currentPage == index + 1,
                        }"
                    >
                        {{ index + 1 }}
                    </li>
                </ul>
            </div>
        </div>
        <div class="content-page">
            <div class="content">
                <div class="navbar-custom">
                    <ul class="list-unstyled topbar-menu float-end mb-0">
                        <li class="notification-list pt-2">
                            <span class="btn btn-success disabled">{{
                                currentTime
                            }}</span>
                        </li>
                    </ul>
                    <button class="button-menu-mobile open-left">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>
                <div
                    class="container-fluid mt-3"
                    id="contentQuestion"
                    v-for="(exam, index) in exams"
                    :key="index"
                >
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Pertanyaan</h4>
                                </div>
                                <div class="card-body">
                                    <p>{{ exam.question }}</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Jawaban</h4>
                                </div>
                                <div class="card-body">
                                    <ul>
                                        <li
                                            v-for="(
                                                answer, index
                                            ) in exam.answers"
                                            :key="index"
                                            class="shadow-sm d-flex align-items-center justify-content-between"
                                        >
                                            <p class="m-0">
                                                {{
                                                    answer.answer == 1
                                                        ? "Benar"
                                                        : "Salah"
                                                }}
                                            </p>
                                            <input
                                                type="radio"
                                                :value="answer.uuid"
                                                :name="`answer_${index}`"
                                                :checked="
                                                    answer.uuid ==
                                                    examAnswer.welderAnswer
                                                        ?.answer?.uuid
                                                "
                                                v-model="participantAnswer"
                                            />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer bg-white">
        <div class="container-fluid">
            <div class="d-flex justify-content-between text-dark">
                <h5>
                    <span
                        v-if="metaPagination.currentPage != 1"
                        style="cursor: pointer"
                        @click="
                            onPageChange(metaPagination.currentPage - 1, false)
                        "
                        >Kembali</span
                    >
                </h5>
                <h5></h5>
                <h5>
                    <span
                        v-if="
                            metaPagination.currentPage !=
                            metaPagination.lastPage
                        "
                        @click="
                            onPageChange(metaPagination.currentPage + 1, true)
                        "
                        style="cursor: pointer"
                        >Selanjutnya</span
                    >
                    <span
                        v-else
                        style="cursor: pointer"
                        data-bs-target="#confirmModal"
                        data-bs-toggle="modal"
                        >Selesai</span
                    >
                </h5>
            </div>
        </div>
    </footer>

    <div
        class="modal fade"
        id="confirmModal"
        tabindex="-1"
        aria-labelledby="confirmModalLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">
                        konfirmasi
                    </h5>
                </div>
                <div
                    class="modal-body"
                    v-html="
                        'harap periksa kembali jawaban anda, jika sudah yakin bisa klik tombol Simpan?'
                    "
                ></div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="handleFinish()"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sidebar-enable .leftside-menu ul {
    padding-right: 0.88rem;
    padding-left: 1rem;
}
.sidebar-enable .leftside-menu ul li {
    width: 100%;
}
.leftside-menu ul {
    flex-wrap: wrap;
    padding-right: 2rem;
    display: flex;
    justify-content: space-between;
    list-style-type: none;
}
.leftside-menu ul li {
    padding: 10px;
    margin-bottom: 20px;
    margin-right: 5px;
    width: 25%;
    border: 1px solid white;
    color: white;
    text-align: center;
    /* cursor: pointer; */
}
.leftside-menu ul li.active {
    /* border: 1px solid #0acf97;
    background-color: #0acf97; */
    border: 1px solid #727cf5;
    background-color: #727cf5;
}

#contentQuestion .card-body ul {
    list-style-type: none;
    padding: 0;
}
#contentQuestion .card-body ul li {
    margin-bottom: 1rem;
    padding: 10px;
    padding-inline: 20px;
    border: 1px solid #fafbfe;
    border-radius: 20px;
}
#contentQuestion .card-body ul li:hover {
    background-color: #fafbfe;
}
#contentQuestion .card-body ul li input {
    cursor: pointer;
    height: 20px;
    width: 20px;
}
</style>
