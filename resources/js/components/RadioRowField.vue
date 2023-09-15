<template>
    <div class="row mb-3">
        <label :class="`col-${length}`" v-html="label" :for="label"></label>
        <div class="col">
            <input
                :type="type"
                class="form-control"
                v-model="model"
                :id="label"
                :class="{ 'is-invalid': errors }"
                :readonly="isLoading"
                :disabled="isDisabled"
                @input="onInput"
            />
            <div
                class="invalid-feedback"
                v-if="errors && errors.length > 0"
                v-for="(error, index) in errors"
                :key="index"
            >
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        length: String,
        label: String,
        value: [String, Number],
        errors: {
            default: null,
            type: Array,
        },
        type: {
            default: "text",
            type: String,
        },
        isDisabled: {
            default: false,
            type: Boolean,
        },
        isLoading: {
            default: false,
            type: Boolean,
        },
    },
    data() {
        return {
            model: "",
        };
    },
    watch: {
        value(newVal) {
            this.model = newVal;
        },
    },
    methods: {
        onInput(e) {
            $("body").on("keyup", ".is-invalid", function () {
                let className = $(this).attr("class").split(" ");
                let invalidClass = className.filter(
                    (name) => name === "is-invalid"
                );
                if (invalidClass) {
                    $(this).removeClass("is-invalid");
                }
            });

            $("body").on("change", ".is-invalid", function () {
                let className = $(this).attr("class").split(" ");
                let invalidClass = className.filter(
                    (name) => name === "is-invalid"
                );
                if (invalidClass) {
                    $(this).removeClass("is-invalid");
                }
            });

            this.model = e.target.value;
            this.$emit("update:value", e.target.value);
        },
    },
};
</script>
