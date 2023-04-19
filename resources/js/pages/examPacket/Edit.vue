<script>
import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    props: ["examPacket"],
    data() {
        return {
            errors: {},
            isLoading: false,
            isEdit: false,
            schedule: null,
        };
    },
    mounted() {
        this.schedule = this.date;
    },
    methods: {
        getSchedule(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        handleEdit() {
            this.isLoading = true;
            let form = {
                name: this.examPacket.name,
                schedule: this.examPacket.date,
                period: this.examPacket.period,
                isPeriod: true,
            };
            this.$store
                .dispatch("updateData", [
                    "exam-packet",
                    this.examPacket.uuid,
                    form,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.isEdit = false;
                    this.$emit("onSuccessEdit", true);
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
    },
};
</script>
<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-primary">
                <div class="card-body profile-user-box">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div>
                                        <h4
                                            class="mt-1 mb-1 text-white"
                                            v-if="!isEdit"
                                        >
                                            {{ examPacket.name }}
                                        </h4>
                                        <input
                                            v-else
                                            type="text"
                                            class="form-control"
                                            v-model="examPacket.name"
                                            :disabled="isLoading"
                                            :class="{
                                                'is-invalid': errors.name,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback"
                                            v-if="errors.name"
                                            v-for="(
                                                error, index
                                            ) in errors.name"
                                            :key="index"
                                        >
                                            {{ error }}.
                                        </div>
                                        <p class="font-13 text-white-50">
                                            Nama Paket
                                        </p>

                                        <ul class="mb-0 list-inline text-light">
                                            <li class="list-inline-item me-3">
                                                <h5
                                                    class="mb-1 text-white"
                                                    v-if="!isEdit"
                                                >
                                                    {{
                                                        getSchedule(
                                                            examPacket.schedule
                                                        )
                                                    }}
                                                </h5>
                                                <input
                                                    v-else
                                                    type="date"
                                                    class="form-control"
                                                    v-model="examPacket.date"
                                                    :disabled="isLoading"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.schedule,
                                                    }"
                                                />
                                                <div
                                                    class="invalid-feedback"
                                                    v-if="errors.schedule"
                                                    v-for="(
                                                        error, index
                                                    ) in errors.schedule"
                                                    :key="index"
                                                >
                                                    {{ error }}.
                                                </div>
                                                <p
                                                    class="mb-0 font-13 text-white-50"
                                                >
                                                    Jadwal Ujian
                                                </p>
                                            </li>
                                            <li class="list-inline-item">
                                                <h5
                                                    class="mb-1 text-white"
                                                    v-if="!isEdit"
                                                >
                                                    {{ examPacket.period }}
                                                    Menit
                                                </h5>
                                                <input
                                                    v-else
                                                    type="number"
                                                    class="form-control"
                                                    v-model="examPacket.period"
                                                    :disabled="isLoading"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.period,
                                                    }"
                                                />
                                                <div
                                                    class="invalid-feedback"
                                                    v-if="errors.period"
                                                    v-for="(
                                                        error, index
                                                    ) in errors.period"
                                                    :key="index"
                                                >
                                                    {{ error }}.
                                                </div>
                                                <p
                                                    class="mb-0 font-13 text-white-50"
                                                >
                                                    Tenggat Pengerjaan
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                <button
                                    type="button"
                                    class="btn btn-success me-2"
                                    @click="handleEdit"
                                    v-if="isEdit"
                                >
                                    Simpan
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-light"
                                    @click="isEdit = true"
                                    v-if="!isEdit"
                                >
                                    Edit Paket
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-light"
                                    @click="isEdit = false"
                                    v-else
                                >
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
