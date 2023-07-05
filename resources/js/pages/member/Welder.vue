<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";
import Util from "../../store/utils/util";
import jsCookie from "js-cookie";

export default {
    components: { PageTitle, Success },
    data() {
        return {
            form: {
                welderSkillIds: [],
                residentIdCard: "",
                dateBirth: "",
                birthPlace: "",
                workingStatus: "",
                documentCertificateSchool: "",
                documentPasPhoto: "",
                documentCertificateCompetency: "",
            },
            welderSkills: [],
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        this.selectUtil();

        let isPayment = JSON.parse(localStorage.getItem("isPayment"));
        if (isPayment) {
            this.$router.push({
                name: "Show Invoice",
                params: {
                    externalId: isPayment.externalId,
                    costId: "welderMember",
                },
            });
        }
    },
    computed: {
        formData() {
            let formData = new FormData();

            for (
                let index = 0;
                index < this.form.welderSkillIds.length;
                index++
            ) {
                formData.append(
                    `welder_skill_ids[${index}]`,
                    this.form.welderSkillIds[index]
                );
            }

            formData.append("resident_id_card", this.form.residentIdCard);
            formData.append("date_birth", this.form.dateBirth);
            formData.append("birth_place", this.form.birthPlace);
            formData.append("working_status", this.form.workingStatus);
            formData.append(
                "document_certificate_school",
                this.form.documentCertificateSchool
            );
            formData.append("document_pas_photo", this.form.documentPasPhoto);
            formData.append(
                "document_certificate_competency",
                this.form.documentCertificateCompetency
            );

            return formData;
        },
    },
    methods: {
        onBack() {
            this.$router.push({ name: "Member" });
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};

            this.$store
                .dispatch("postDataUpload", [
                    "user/welder-member",
                    this.formData,
                ])
                .then((response) => {
                    this.isLoading = false;
                    localStorage.setItem(
                        "isPayment",
                        JSON.stringify({
                            paymentType: "welderMember",
                            externalId: response.data.externalId,
                        })
                    );

                    window.location.href =
                        "/invoice/" +
                        response.data.externalId +
                        "/welderMember";
                })
                .catch((error) => {
                    this.isLoading = false;

                    this.errors = error.response.data.messages;

                    if (
                        error.response.data.status == "ERROR" &&
                        error.response.data.statusCode == 500
                    ) {
                        this.isError = true;
                        this.msg = error.response.data.message;
                    }
                });
        },
        selectUtil() {
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
        uploadCertificateSchool(e) {
            this.form.documentCertificateSchool = e.target.files[0];
        },
        uploadPasPhoto(e) {
            this.form.documentPasPhoto = e.target.files[0];
        },
        uploadCertificateCompetency(e) {
            this.form.documentCertificateCompetency = e.target.files[0];
        },
    },
};
</script>

<template>
    <PageTitle title="Daftar Member Welder" :isBack="true" @onBack="onBack" />

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label>NIK</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.residentIdCard"
                        :class="{ 'is-invalid': errors.residentIdCard }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.residentIdCard"
                        v-for="(error, index) in errors.residentIdCard"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Tempat Lahir</label
                    ><input
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
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Tanggal Lahir</label
                    ><input
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
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Status Bekerja</label>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            value="1"
                            v-model="form.workingStatus"
                            :class="{ 'is-invalid': errors.workingStatus }"
                            :disabled="isLoading"
                        /><label class="form-check-label"> Ya </label>
                    </div>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            value="0"
                            v-model="form.workingStatus"
                            :class="{ 'is-invalid': errors.workingStatus }"
                            :disabled="isLoading"
                        /><label class="form-check-label"> Tidak </label>
                        <div
                            class="invalid-feedback"
                            v-if="errors.workingStatus"
                            v-for="(error, index) in errors.workingStatus"
                            :key="index"
                        >
                            {{ error }}
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Jenis Kompetensi</label
                    ><select
                        class="select2-hidden-accessible"
                        ref="welderSkill"
                        :class="{ 'is-invalid': errors.welderSkillIds }"
                        :disabled="isLoading"
                        multiple
                    ></select>
                    <div
                        class="invalid-feedback"
                        v-if="errors.welderSkillIds"
                        v-for="(error, index) in errors.welderSkillIds"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label>Pas Foto Formal Berwarna</label
                    ><input
                        type="file"
                        class="form-control"
                        :class="{ 'is-invalid': errors.documentPasPhoto }"
                        :disabled="isLoading"
                        @change="uploadPasPhoto"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentPasPhoto"
                        v-for="(error, index) in errors.documentPasPhoto"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-3">
                    <label
                        >Sertifikat Pelatihan/Kompetensi Keahlian Pengelasan </label
                    ><input
                        type="file"
                        class="form-control"
                        :class="{
                            'is-invalid': errors.documentCertificateCompetency,
                        }"
                        :disabled="isLoading"
                        @change="uploadCertificateCompetency"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.documentCertificateCompetency"
                        v-for="(
                            error, index
                        ) in errors.documentCertificateCompetency"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
            </div>
            <div class="card-footer border-top d-flex justify-content-between">
                <router-link
                    :to="{ name: 'Member' }"
                    class="btn btn-sm btn-secondary"
                    :disabled="isLoading"
                    >Batal</router-link
                >
                <button class="btn btn-sm btn-primary" v-if="!isLoading">
                    Simpan
                </button>
                <button
                    class="btn btn-sm btn-primary"
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

    <Success
        :url="{ name: 'Dashboard' }"
        :msg="'data berhasil diregistrasi.'"
    />
</template>
