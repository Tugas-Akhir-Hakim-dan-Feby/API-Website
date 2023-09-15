<template>
    <div class="mb-3">
        <label v-html="label" :for="label"></label>
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
</template>

<script>
export default {
    props: {
        label: String,
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
    methods: {
        onInput(e) {
            this.model = e.target.value;
            this.$emit("update:value", e.target.value);
        },
    },
};
</script>
