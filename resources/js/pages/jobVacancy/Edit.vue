<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";

import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    props: ["id"],
    data() {
        return {
            isLoading: false,
            welderSkills: [],
            form: {
                welderSkillId: "",
                workType: "",
                placement: "",
                salary: "",
                deadline: "",
                description: "",
                documentPamphlet: null,
            },
            errors: {},
            minDate: new Date().toISOString().split("T")[0],
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("welder_skill_id", this.form.welderSkillId);
            formData.append("work_type", this.form.workType);
            formData.append("placement", this.form.placement);
            formData.append("salary", this.form.salary);
            formData.append("deadline", this.form.deadline);
            formData.append("description", this.form.description);
            formData.append("contact", this.form.contact);
            formData.append("contact_name", this.form.contactName);

            if (this.form.documentPamphlet) {
                formData.append(
                    "document_pamphlet",
                    this.form.documentPamphlet
                );
            }

            formData.append("_method", "put");

            return formData;
        },
    },
    mounted() {
        this.getWelderSkills();
        this.getJobVacancy();
    },
    methods: {
        setForm(jobVacancy) {
            this.form = {
                welderSkillId: jobVacancy.welderSkill?.uuid,
                workType: jobVacancy.workType,
                placement: jobVacancy.placement,
                salary: jobVacancy.salary,
                contact: jobVacancy.contact,
                contactName: jobVacancy.contactName,
                deadline: this.getDeadline(jobVacancy.deadline),
                description: jobVacancy.description,
                pamphlet: jobVacancy.pamphlet,
            };
        },
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", ""])
                .then((response) => {
                    this.welderSkills = response.data;
                })
                .catch((err) => {});
        },
        getJobVacancy() {
            this.isLoading = true;

            this.$store
                .dispatch("showData", ["job-vacancy", this.id])
                .then((response) => {
                    this.isLoading = false;
                    this.setForm(response.data);
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getDeadline(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
        handleSubmit() {
            this.errors = {};
            this.isLoading = true;

            this.$store
                .dispatch("postDataUpload", [
                    "job-vacancy/" + this.id,
                    this.formData,
                ])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        onBack() {
            this.$router.push({ name: "Job Vacancy" });
        },
        uploadDocumentPamphlet(e) {
            this.form.documentPamphlet = e.target.files[0];
        },
    },
    components: { PageTitle, Success },
};
</script>

<template>
    <PageTitle title="Edit Lowongan" :isBack="true" @onBack="onBack">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Job Vacancy' }"
                    >Lowongan</router-link
                >
            </li>
            <li class="breadcrumb-item active">Edit Lowongan</li>
        </ol>
    </PageTitle>

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <label>Jenis Lowongan</label>
                    <select
                        class="form-select"
                        v-model="form.welderSkillId"
                        :class="{ 'is-invalid': errors.welderSkillId }"
                        :disabled="isLoading"
                    >
                        <option disabled selected></option>
                        <option
                            :value="welderSkill.uuid"
                            v-for="(welderSkill, index) in welderSkills"
                            :key="index"
                            v-html="welderSkill.skillName"
                        ></option>
                    </select>
                    <div
                        class="invalid-feedback"
                        v-if="errors.welderSkillId"
                        v-for="(error, index) in errors.welderSkillId"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Jenis Pekerjaan</label>
                    <select
                        class="form-select"
                        v-model="form.workType"
                        :class="{ 'is-invalid': errors.workType }"
                        :disabled="isLoading"
                    >
                        <option disabled selected></option>
                        <option value="Penuh Waktu">Penuh Waktu</option>
                        <option value="Paruh Waktu">Paruh Waktu</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Magang">Magang</option>
                    </select>
                    <div
                        class="invalid-feedback"
                        v-if="errors.workType"
                        v-for="(error, index) in errors.workType"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Kontak Yang Dihubungi</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.contact"
                        :class="{ 'is-invalid': errors.contact }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.contact"
                        v-for="(error, index) in errors.contact"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>Nama Kontak Yang Dihubungi</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.contactName"
                        :class="{ 'is-invalid': errors.contactName }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.contactName"
                        v-for="(error, index) in errors.contactName"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>Penempatan Kerja</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.placement"
                        :class="{ 'is-invalid': errors.placement }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.placement"
                        v-for="(error, index) in errors.placement"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Perkiraan Gaji</label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="form.salary"
                        min="1000"
                        :class="{ 'is-invalid': errors.salary }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.salary"
                        v-for="(error, index) in errors.salary"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Waktu Penutupan</label>
                    <input
                        type="date"
                        class="form-control"
                        v-model="form.deadline"
                        :class="{ 'is-invalid': errors.deadline }"
                        :disabled="isLoading"
                        :min="minDate"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.deadline"
                        v-for="(error, index) in errors.deadline"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Deskripsi Pekerjaan</label>
                    <QuillEditor
                        theme="snow"
                        toolbar="essential"
                        style="height: 300px"
                        contentType="html"
                        v-model:content="form.description"
                        :style="{
                            borderColor: errors.articleContent ? '#fa5c7c' : '',
                        }"
                    />
                    <div
                        style="
                            width: 100%;
                            margin-top: 0.25rem;
                            font-size: 0.75rem;
                            color: #fa5c7c;
                        "
                        v-if="errors.description"
                        v-for="(error, index) in errors.description"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <router-link
                    :to="{ name: 'Job Vacancy' }"
                    class="btn btn-sm btn-secondary"
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
        :url="{ name: 'Job Vacancy' }"
        :msg="'data berhasil diperbaharui.'"
    />
</template>
