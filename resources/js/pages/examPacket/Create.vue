<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
import Util from "../../store/utils/util";

export default {
    data() {
        return {
            form: {
                name: "",
                schedule: "",
                startTime: "",
                endTime: "",
            },
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        Util.removeInvalidClass();
    },
    methods: {
        handleSubmit() {
            this.isLoading = true;
            this.$store
                .dispatch("postData", ["exam-packet", this.form])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        onCancel() {
            this.$router.back(-1);
        },
    },
    components: { PageTitle, Success },
};
</script>

<template>
    <PageTitle title="Tambah Paket" />

    <div class="row">
        <div class="col-lg-6">
            <form @submit.prevent="handleSubmit" method="post">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <label for="name">Nama Paket</label>
                            <input
                                type="text"
                                class="form-control form-validation"
                                id="name"
                                :class="{ 'is-invalid': errors.name }"
                                v-model="form.name"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.name"
                                v-for="(error, index) in errors.name"
                                :key="index"
                            >
                                {{ error }}.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="schedule">Jadwal Ujian</label>
                            <input
                                type="date"
                                class="form-control form-validation"
                                id="schedule"
                                :class="{ 'is-invalid': errors.schedule }"
                                v-model="form.schedule"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.schedule"
                                v-for="(error, index) in errors.schedule"
                                :key="index"
                            >
                                {{ error }}.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Tenggat Pengerjaan</label>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <input
                                        type="time"
                                        class="form-control form-validation"
                                        id="startTime"
                                        :class="{
                                            'is-invalid': errors.startTime,
                                        }"
                                        v-model="form.startTime"
                                        :disabled="isLoading"
                                    />
                                    <small>* masukan waktu mulai</small>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.startTime"
                                        v-for="(
                                            error, index
                                        ) in errors.startTime"
                                        :key="index"
                                    >
                                        {{ error }}.
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <input
                                        type="time"
                                        class="form-control form-validation"
                                        id="endTime"
                                        :class="{
                                            'is-invalid': errors.endTime,
                                        }"
                                        v-model="form.endTime"
                                        :disabled="isLoading"
                                    />
                                    <small>* masukan waktu selesai</small>
                                    <div
                                        class="invalid-feedback"
                                        v-if="errors.endTime"
                                        v-for="(error, index) in errors.endTime"
                                        :key="index"
                                    >
                                        {{ error }}.
                                    </div>
                                </div>
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
        </div>
    </div>

    <Success
        :url="{ name: 'Exam Packet' }"
        :msg="'data berhasil ditambahkan.'"
    />
</template>
