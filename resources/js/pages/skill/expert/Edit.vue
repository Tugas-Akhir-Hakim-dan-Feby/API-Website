<script>
import Success from "../../../components/notifications/Success.vue";

export default {
    props: ["uuid"],
    mounted() {
        this.getSkill();
    },
    data() {
        return {
            skill: {},
            errors: {},
            isLoading: false,
        };
    },
    methods: {
        onCancel() {
            this.$emit("onCancel", true);
        },
        getSkill() {
            this.$store
                .dispatch("showData", ["skill/expert", this.uuid])
                .then((response) => {
                    this.skill = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        handleSubmit() {
            this.isLoading = true;
            this.error = {};
            this.$store
                .dispatch("updateData", ["skill/expert", this.uuid, this.skill])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                    this.$emit("onCancel", true);
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
    },
    components: { Success },
};
</script>
<template>
    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <label for="skillName">Nama Keahlian</label>
                    <input
                        type="text"
                        class="form-control"
                        id="skillName"
                        :class="{ 'is-invalid': errors.skillName }"
                        v-model="skill.skillName"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.skillName"
                        v-for="(error, index) in errors.skillName"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label for="skillDescription">Deskripsi</label>
                    <textarea
                        class="form-control"
                        rows="5"
                        :class="{ 'is-invalid': errors.skillDescription }"
                        v-model="skill.skillDescription"
                        :disabled="isLoading"
                    >
                    Deskripsi Keahlian 1</textarea
                    >
                    <div
                        class="invalid-feedback"
                        v-if="errors.skillDescription"
                        v-for="(error, index) in errors.skillDescription"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button
                    class="btn btn-secondary"
                    @click="onCancel"
                    :disabled="isLoading"
                >
                    Batal
                </button>
                <button class="btn btn-success" v-if="!isLoading">
                    Simpan
                </button>
                <button
                    class="btn btn-success"
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

    <Success :msg="'data berhasil diperbaharui.'" />
</template>
