<script>
import Loader from "../../../components/Loader.vue";
import iziToast from "izitoast";
import cookie from "js-cookie";

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
            errors: {},
            form: {
                keyPacket: "",
            },
        };
    },
    beforeMount() {
        let examSession = cookie.get("examSession");
        if (examSession) {
            setTimeout(() => {
                this.examId = this.$route.params.examId;
                this.examPacketId = this.$route.params.examPacketId;
                this.getExams();
                this.getExamPacket();
            }, 1000);
            setTimeout(() => {
                this.getMinute(
                    this.examPacket.startTime,
                    this.examPacket.endTime
                );
            }, 2000);
        } else {
            $("#loginExam").modal("show");
        }
    },
    mounted() {
        let examSession = cookie.get("examSession");

        if (examSession) {
            this.numbering = localStorage.getItem("numbering");
            $("body").on("keydown", this.onDisabled);
            document.addEventListener("visibilitychange", this.onDisabled);
        } else {
            $("#loginExam").modal("show");
        }
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
        handleLogin() {
            this.errors = {};
            this.isLoading = true;
            const params = {
                examPacketId: this.$route.params.examPacketId,
                keyPacket: this.form.keyPacket,
            };

            this.$store
                .dispatch("postData", ["user-exam-packet/key-check", params])
                .then((response) => {
                    this.isLoading = false;
                    cookie.set("examSession", true);
                    window.location.reload();
                    $("#loginExam").modal("hide");
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (
                        error.response.data.status == "WARNING" &&
                        error.response.data.statusCode == 400
                    ) {
                        iziToast.error({
                            message: error.response.data.message,
                            position: "topCenter",
                        });
                    } else {
                        this.errors = error.response.data.messages;
                    }
                });
        },
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
        onDisabled(e) {
            e.preventDefault();

            if (
                (e.ctrlKey || e.metaKey) &&
                (e.key == "p" ||
                    e.keyCode == 87 ||
                    e.charCode == 16 ||
                    e.charCode == 112 ||
                    e.charCode == 9 ||
                    e.keyCode == 80)
            ) {
                e.preventDefault();
            }

            iziToast.error({
                message: "harap kerjakan dengan jujur!",
                position: "topCenter",
            });
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
                    localStorage.removeItem("pageStory");
                    cookie.remove("examSession");
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
        refreshPage() {
            this.getExams();
        },
        onBack() {
            $("#loginExam").modal("hide");
            window.location.href = "/exam-packet";
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
                        <li class="notification-list pt-2 me-3">
                            <button
                                class="btn btn-sm btn-primary mdi mdi-autorenew"
                                @click="refreshPage"
                            ></button>
                        </li>
                        <li class="notification-list pt-2">
                            <span class="btn btn-sm btn-success disabled">{{
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
        id="loginExam"
        tabindex="-1"
        aria-labelledby="loginExamLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginExamLabel">konfirmasi</h5>
                </div>
                <form @submit.prevent="handleLogin" method="post">
                    <div class="modal-body">
                        <div>
                            <label>Masukan Kunci Paket</label>
                            <input
                                type="password"
                                class="form-control"
                                :class="{ 'is-invalid': errors.keyPacket }"
                                v-model="form.keyPacket"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.keyPacket"
                                v-for="(error, index) in errors.keyPacket"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            @click.native="onBack"
                        >
                            Kembali
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Kirim
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                        class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        @click="handleFinish()"
                        v-if="!isLoading"
                    >
                        Simpan
                    </button>
                    <button
                        class="btn btn-sm btn-primary"
                        type="button"
                        disabled
                        v-if="isLoading"
                    >
                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Harap Tunggu...
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
