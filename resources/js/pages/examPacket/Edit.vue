<script>
import dayjs from "dayjs";
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
import Util from "../../store/utils/util";
import jsCookie from "js-cookie";
import "select2";

export default {
    props: ["id"],
    data() {
        return {
            form: {
                name: "",
                examSchedule: "",
                closeSchedule: "",
                startTime: "",
                endTime: "",
            },
            welderSkills: [],
            minDate: new Date().toISOString().split("T")[0],
            errors: {},
            user: {},
            isLoading: false,
        };
    },
    mounted() {
        this.util();
        this.getUser();
        this.getExamPacket();
        this.getWelderSkills();
        Util.removeInvalidClass();
    },
    watch: {
        id(newId) {
            this.id = newId;
        },
    },
    methods: {
        getExamPacket() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["exam-packet", this.id])
                .then((response) => {
                    this.isLoading = false;
                    this.form = {
                        welderSkillId: response.data.competenceSchema?.uuid,
                        operatorId: response.data.operator?.uuid,
                        examSchedule: this.formatDate(
                            response.data.examSchedule
                        ),
                        closeSchedule: this.formatDate(
                            response.data.closeSchedule
                        ),
                        startTime: response.data.startTime,
                        endTime: response.data.endTime,
                        price: response.data.price,
                    };
                });
        },
        getExpertUsers() {
            this.$store
                .dispatch("getData", ["user/expert", {}])
                .then((response) => {
                    this.experUsers = response.data.map((user) => ({
                        id: user.uuid,
                        text: user.name,
                    }));
                })
                .catch((err) => {});
        },
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.user = response.user;
                });
        },
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", ""])
                .then((response) => {
                    this.welderSkills = response.data;
                });
        },
        handleSubmit() {
            this.isLoading = true;

            if (this.user.roleId == 8) {
                this.form.operatorId = this.user.operator.uuid;
            }

            this.form.method = "put";

            this.$store
                .dispatch("updateData", ["exam-packet", this.id, this.form])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        util() {
            $(this.$refs.tuk).select2({
                ajax: {
                    url: `${this.$store.state.BASE_URL}/api/v1/user/operator`,
                    dataType: "json",
                    headers: {
                        Authorization: "Bearer " + jsCookie.get("token"),
                    },
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            per_page: 10,
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data.map((user) => ({
                                id: user.operator?.uuid,
                                text: `${user.operator?.tuk_type} / ${user.operator?.tuk_name} / ${user.operator?.tuk_code}`,
                            })),
                        };
                    },
                    cache: true,
                },
            });
            $(this.$refs.tuk).on("change", () => {
                this.form.operatorId = $(this.$refs.tuk).val();
                $(".select2-hidden-accessible").removeClass("is-invalid");
            });
        },
        formatDate(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
        onCancel() {
            this.$router.back(-1);
        },
    },
    components: { PageTitle, Success },
};
</script>

<template>
    <PageTitle title="Edit Paket" isBack="true" @onBack="$router.back(-1)">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Exam Packet' }">
                    <span> Uji Kompetensi </span>
                </router-link>
            </li>
            <li class="breadcrumb-item active">Edit Paket</li>
        </ol>
    </PageTitle>

    <div class="row">
        <div class="col-lg-12">
            <form @submit.prevent="handleSubmit" method="post">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <label for="name">Skema Kompetensi</label>
                            <select
                                class="form-select form-validation"
                                :class="{ 'is-invalid': errors.welderSkillId }"
                                v-model="form.welderSkillId"
                                :disabled="isLoading"
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="(welderSkill, index) in welderSkills"
                                    :key="index"
                                    :value="welderSkill.uuid"
                                    v-html="welderSkill.skillName"
                                ></option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.welderSkillId"
                                v-for="(error, index) in errors.welderSkillId"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="schedule">Jadwal Ujian</label>
                            <input
                                type="date"
                                class="form-control form-validation"
                                id="schedule"
                                :class="{ 'is-invalid': errors.examSchedule }"
                                v-model="form.examSchedule"
                                :disabled="isLoading"
                                :min="minDate"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.examSchedule"
                                v-for="(error, index) in errors.examSchedule"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="schedule"
                                >Jadwal Penutupan Pendaftaran</label
                            >
                            <input
                                type="date"
                                class="form-control form-validation"
                                id="schedule"
                                :class="{ 'is-invalid': errors.closeSchedule }"
                                v-model="form.closeSchedule"
                                :disabled="isLoading"
                                :min="minDate"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.closeSchedule"
                                v-for="(error, index) in errors.closeSchedule"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div>
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
                                        {{ error }}
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
                                        {{ error }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="schedule">Harga Uji Kompetensi</label>
                            <input
                                type="number"
                                class="form-control form-validation"
                                id="schedule"
                                :class="{ 'is-invalid': errors.price }"
                                v-model="form.price"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.price"
                                v-for="(error, index) in errors.price"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <router-link
                            :to="{
                                name: 'Exam Packet Detail',
                                params: { id: id },
                            }"
                            class="btn btn-sm btn-secondary"
                            :disabled="isLoading"
                        >
                            Batal
                        </router-link>
                        <button
                            class="btn btn-sm btn-success"
                            v-if="!isLoading"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-sm btn-success"
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
        :url="{
            name: 'Exam Packet Detail',
            params: { id: id },
        }"
        :msg="'data berhasil diperbaharui.'"
    />
</template>
