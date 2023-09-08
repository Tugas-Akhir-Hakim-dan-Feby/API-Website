<script>
import Success from "../../../components/notifications/Success.vue";
import iziToast from "izitoast";
import dayjs from "dayjs";
import "dayjs/locale/id";
import UploadFileWelderMember from "./uploadFile/UploadFileWelderMember.vue";
import jsCookie from "js-cookie";

export default {
    data() {
        return {
            form: {},
            errors: {},
            document: {},
            welderSkills: [],
            isLoading: false,
        };
    },
    mounted() {
        this.getUser();
        this.getWelderSkills();
    },
    methods: {
        setForm(user) {
            this.form.name = user.name;
            this.form.email = user.email;
            this.form.residentIdCard = user.welderMember?.residentIdCard;
            this.form.dateBirth = this.getDateBirth(
                user.welderMember?.dateBirth
            );
            this.form.birthPlace = user.welderMember?.birthPlace;
            this.form.workingStatus = user.welderMember?.workingStatus;
            this.form.id = user.uuid;

            this.form.welderHasSkills = user.welderHasSkills;

            this.document = {
                id: user.uuid,
                certificateSchool: user.welderMember?.certificateSchool,
                pasPhoto: user.welderMember?.pasPhoto,
                competencyCertificates: user.welderDocuments,
            };
        },
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", {}])
                .then((response) => {
                    this.welderSkills = response.data;
                })
                .catch((error) => {});
        },
        getUser() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.isLoading = false;
                    this.setForm(response.user);
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getDateBirth(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
        getWelderSkills() {
            $(this.$refs.welderSkill).select2({
                ajax: {
                    url: `${this.$store.state.BASE_URL}/api/v1/skill/welder`,
                    dataType: "json",
                    headers: {
                        Authorization: "Bearer " + jsCookie.get("token"),
                    },
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            per_page: 20,
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data.map((skill) => ({
                                id: skill.uuid,
                                text: skill.skill_name,
                            })),
                        };
                    },
                    cache: true,
                },
            });

            $(this.$refs.welderSkill).on("change", () => {
                this.form.welderSkillIds = $(this.$refs.welderSkill).val();
                $(".select2-hidden-accessible").removeClass("is-invalid");
            });
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", [
                    "user/welder-member",
                    this.form.id,
                    this.form,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.getUser();
                    iziToast.success({
                        title: "Selamat",
                        message: "data anda berhasil diperbaharui",
                        position: "topCenter",
                    });
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        onSuccessUploadDocument(e) {
            this.getUser();
        },
    },
    components: { Success, UploadFileWelderMember },
};
</script>
<template>
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>Informasi Member</h4>
            <p>Perbaharui informasi member Anda.</p>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <form method="post" @submit.prevent="handleSubmit">
                    <div class="card-body">
                        <div
                            class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert"
                            v-if="typeof errors == 'string'"
                        >
                            <button
                                type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>
                            <strong>Galat - </strong> {{ errors }}
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3">NIK</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.residentIdCard"
                                    :class="{
                                        'is-invalid': errors.residentIdCard,
                                    }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.residentIdCard"
                                    v-for="(
                                        error, index
                                    ) in errors.residentIdCard"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Tempat Lahir</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.birthPlace"
                                    :class="{ 'is-invalid': errors.birthPlace }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.birthPlace"
                                    v-for="(error, index) in errors.birthPlace"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Tanggal Lahir</label>
                            <div class="col">
                                <input
                                    type="date"
                                    class="form-control"
                                    v-model="form.dateBirth"
                                    :class="{ 'is-invalid': errors.dateBirth }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.dateBirth"
                                    v-for="(error, index) in errors.dateBirth"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Status Bekerja</label>
                            <div class="col">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        value="1"
                                        v-model="form.workingStatus"
                                        :class="{
                                            'is-invalid': errors.workingStatus,
                                        }"
                                        :disabled="isLoading"
                                    /><label class="form-check-label">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        value="0"
                                        v-model="form.workingStatus"
                                        :class="{
                                            'is-invalid': errors.workingStatus,
                                        }"
                                        :disabled="isLoading"
                                    /><label class="form-check-label">
                                        Tidak
                                    </label>
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.workingStatus"
                                    v-for="(
                                        error, index
                                    ) in errors.workingStatus"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label class="col-sm-3">Jenis Kompetensi</label>
                            <div class="col">
                                <select
                                    class="select2-hidden-accessible form-select"
                                    ref="welderSkill"
                                    :class="{
                                        'is-invalid': errors.welderSkillIds,
                                    }"
                                    :disabled="isLoading"
                                    multiple
                                ></select>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.welderSkillIds"
                                    v-for="(
                                        error, index
                                    ) in errors.welderSkillIds"
                                    :key="index"
                                >
                                    {{ error }}
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3">&nbsp;</label>
                            <div class="col">
                                <ul style="padding-left: 1.2rem; margin-top: 0">
                                    <li
                                        v-for="(
                                            welderSkill, index
                                        ) in form.welderHasSkills"
                                        :key="index"
                                    >
                                        {{ welderSkill.welderSkill.skillName }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button
                            class="btn btn-sm btn-success"
                            v-if="!isLoading"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-success btn-sm"
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
                </form>
            </div>
        </div>
    </div>

    <UploadFileWelderMember :document="document" />
</template>
