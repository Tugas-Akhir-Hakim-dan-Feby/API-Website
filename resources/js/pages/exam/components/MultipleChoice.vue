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
            answers: [],
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
            this.$emit("onAnswers", [this.answers, this.correctAnswer]);
        },
        sendAnswer() {
            this.answerMethod();
        },
    },
};
</script>
<template>
    <div class="row">
        <div class="col-lg-6">
            <div class="mb-3">
                <label>Jawaban A</label>
                <input
                    type="text"
                    class="form-control form-validation"
                    v-model="answers[0]"
                    :class="{ 'is-invalid': errors.answers }"
                    :disabled="isLoading"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.answers"
                    v-for="(error, index) in errors.answers"
                    :key="index"
                >
                    {{ error }}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label>Jawaban B</label>
                <input
                    type="text"
                    class="form-control form-validation"
                    v-model="answers[1]"
                    :class="{ 'is-invalid': errors.answers }"
                    :disabled="isLoading"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.answers"
                    v-for="(error, index) in errors.answers"
                    :key="index"
                >
                    {{ error }}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label>Jawaban C</label>
                <input
                    type="text"
                    class="form-control form-validation"
                    v-model="answers[2]"
                    :class="{ 'is-invalid': errors.answers }"
                    :disabled="isLoading"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.answers"
                    v-for="(error, index) in errors.answers"
                    :key="index"
                >
                    {{ error }}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label>Jawaban D</label>
                <input
                    type="text"
                    class="form-control form-validation"
                    v-model="answers[3]"
                    :class="{ 'is-invalid': errors.answers }"
                    :disabled="isLoading"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.answers"
                    v-for="(error, index) in errors.answers"
                    :key="index"
                >
                    {{ error }}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label>Jawaban E</label>
                <input
                    type="text"
                    class="form-control form-validation"
                    v-model="answers[4]"
                    :class="{ 'is-invalid': errors.answers }"
                    :disabled="isLoading"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors.answers"
                    v-for="(error, index) in errors.answers"
                    :key="index"
                >
                    {{ error }}
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label>Jawaban Benar</label>
        <select
            class="form-select form-validation"
            v-model="correctAnswer"
            @change="sendAnswer()"
            :class="{ 'is-invalid': errors.answers }"
            :disabled="isLoading"
        >
            <option
                v-for="(answer, index) in answers"
                :key="index"
                :value="answer"
            >
                {{ answer }}
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
