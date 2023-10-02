<script>
import Util from "../../../store/utils/util";

export default {
    props: {
        isEdit: {
            default: false,
        },
        errors: {
            default: null,
        },
        isLoading: {
            default: false,
        },
        answersData: {
            default: null,
        },
        correctAnswerData: {
            default: "",
            type: String,
        },
    },
    data() {
        return {
            answers: [0, 1],
            correctAnswer: "",
        };
    },
    beforeMount() {
        if (this.isEdit) {
            this.correctAnswer = this.correctAnswerData;
            this.answers = this.answersData;
        }
    },
    mounted() {
        Util.removeInvalidClass();
        this.answerMethod();
    },
    methods: {
        answerMethod() {
            this.$emit("answers", [this.answers, this.correctAnswer]);
        },
        sendAnswer() {
            this.answerMethod();
        },
    },
};
</script>
<template>
    <div class="mb-3">
        <label>Jawaban Benar</label>
        <select
            v-model="correctAnswer"
            class="form-select form-validation"
            @change="sendAnswer()"
            :class="{ 'is-invalid': errors.answers }"
            :disabled="isLoading"
        >
            <option value="" selected disabled></option>
            <option
                :value="index"
                v-for="(answer, index) in answers"
                :key="index"
            >
                {{ answer == 1 ? "Benar" : "Salah" }}
            </option>
        </select>
        <div
            class="invalid-feedback"
            v-if="errors.answers"
            v-for="(error, index) in errors.answers"
            :key="index"
        >
            {{ error }}
        </div>
    </div>
</template>
