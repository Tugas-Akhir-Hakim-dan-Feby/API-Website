<script>
import Success from "../../../components/notifications/Success.vue";

export default {
    props: ["uuid"],
    data() {
        return {
            isLoading: false,
            skill: {},
            errors: {},
        };
    },
    mounted() {
        this.getSkill();
    },
    methods: {
        getSkill() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["skill/welder", this.uuid])
                .then((response) => {
                    this.isLoading = false;
                    this.skill = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        onCancel() {
            this.$emit("onCancel", true);
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["skill/welder", this.uuid, this.skill])
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
                        v-model="skill.skillName"
                        :disabled="isLoading"
                        :class="{ 'is-invalid': errors.skillName }"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.skillName"
                        v-for="(error, index) in errors.skillName"
                        :key="index"
                        v-html="error"
                    ></div>
                </div>
                <div class="mb-2">
                    <label for="skillDescription">Deskripsi</label>
                    <textarea
                        class="form-control"
                        rows="5"
                        v-model="skill.skillDescription"
                        :disabled="isLoading"
                        :class="{ 'is-invalid': errors.skillDescription }"
                    ></textarea>
                    <div
                        class="invalid-feedback"
                        v-if="errors.skillDescription"
                        v-for="(error, index) in errors.skillDescription"
                        :key="index"
                        v-html="error"
                    ></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button class="btn btn-secondary" @click="onCancel">
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
