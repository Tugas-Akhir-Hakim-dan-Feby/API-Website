<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
import MultipleChoice from "./components/MultipleChoice.vue";
import TrueFalse from "./components/TrueFalse.vue";
import Util from "../../store/utils/util";
import { AnswerQuestion } from "../../store/utils/constants";

export default {
    props: ["id"],
    data() {
        return {
            form: {
                examPacketId: this.id,
                question: "",
                correctAnswer: "",
                answers: [],
                answerType: AnswerQuestion.MULTIPLE_CHOICE,
                file: null,
            },
            errors: {},
            isLoading: false,
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("exam_packet_id", this.form.examPacketId);
            formData.append("question", this.form.question);
            formData.append("correct_answer", this.form.correctAnswer);
            formData.append("answer_type", this.form.answerType);

            for (let index = 0; index < this.form.answers.length; index++) {
                formData.append(`answers[${index}]`, this.form.answers[index]);
            }

            if (this.form.file) {
                formData.append("file", this.form.file);
            }

            return formData;
        },
    },
    methods: {
        getMultipleChoiceData() {
            return AnswerQuestion.MULTIPLE_CHOICE;
        },
        getTrueFalseData() {
            return AnswerQuestion.TRUE_FALSE;
        },
        getAnswerQuestionData(type) {
            return Util.getAnswerQuestionData(type);
        },
        handleSubmit() {
            this.isLoading = true;
            this.$store
                .dispatch("postDataUpload", ["exam", this.formData])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        answerTrueFalse(e) {
            this.form.answers = e[0];
            this.form.correctAnswer = e[1];
        },
        answerMultipleChoice(e) {
            this.form.answers = e[0];
            this.form.correctAnswer = e[1];
        },
        onUploadFile(e) {
            this.form.file = e.target.files[0];
        },
    },
    components: { PageTitle, Success, MultipleChoice, TrueFalse },
};
</script>

<template>
    <PageTitle title="Tambah Pertanyaan">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Exam Packet' }">
                    <span> Uji Kompetensi </span>
                </router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link
                    :to="{
                        name: 'Exam Packet Detail',
                        params: { id: id },
                    }"
                >
                    <span> Detail Paket </span>
                </router-link>
            </li>
            <li class="breadcrumb-item active">Tambah Pertanyaan</li>
        </ol>
    </PageTitle>

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <label for="question">Pertanyaan</label>
                    <textarea
                        id="question"
                        class="form-control form-validation"
                        :class="{ 'is-invalid': errors.question }"
                        v-model="form.question"
                        :disabled="isLoading"
                        rows="3"
                    ></textarea>
                    <div
                        class="invalid-feedback"
                        v-if="errors.question"
                        v-for="(error, index) in errors.question"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Pilih Tipe Jawaban</label>
                    <select class="form-select" v-model="form.answerType">
                        <option :value="getMultipleChoiceData()">
                            {{ getAnswerQuestionData(getMultipleChoiceData()) }}
                        </option>
                        <option :value="getTrueFalseData()">
                            {{ getAnswerQuestionData(getTrueFalseData()) }}
                        </option>
                    </select>
                </div>
                <MultipleChoice
                    v-if="form.answerType == 'MultipleChoice'"
                    @onAnswers="answerMultipleChoice($event)"
                    :errors="errors"
                    :is-loading="isLoading"
                />
                <TrueFalse
                    v-if="form.answerType == 'TrueFalse'"
                    @answers="answerTrueFalse($event)"
                    :errors="errors"
                    :is-loading="isLoading"
                />
                <div class="mb-2">
                    <label>Unggah File/Foto Pendukung</label>
                    <input
                        type="file"
                        class="form-control"
                        @change="onUploadFile"
                    />
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <router-link
                    class="btn btn-sm btn-secondary"
                    :to="{
                        name: 'Exam Packet Detail',
                        params: { id: id },
                    }"
                    :disabled="isLoading"
                >
                    Batal
                </router-link>
                <button class="btn btn-sm btn-primary" v-if="!isLoading">
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
    </form>

    <Success
        :url="{ name: 'Exam Packet Detail', params: { id: id } }"
        :msg="'data berhasil ditambahkan.'"
    />
</template>
