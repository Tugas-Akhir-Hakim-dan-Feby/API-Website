<script>
import PageTitle from "../../components/PageTitle.vue";
import Success from "../../components/notifications/Success.vue";

import jsCookie from "js-cookie";

export default {
    data() {
        return {
            isLoading: false,
            welderSkills: [],
            companyMembers: [],
            form: {
                welderSkillId: "",
                workType: "",
                placement: "",
                salary: "",
                contact: "",
                deadline: "",
                description: "",
                companyMemberId: "",
            },
            user: {},
            roles: [],
            errors: {},
            minDate: new Date().toISOString().split("T")[0],
        };
    },
    mounted() {
        this.getUser();
        this.getWelderSkills();
        this.getCompanyMembers();
    },
    methods: {
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.user = response.user;
                    this.roles = response.roles;
                })
                .catch((error) => {});
        },
        getCompanyMembers() {
            $(this.$refs.companyMember).select2({
                ajax: {
                    url: `${this.$store.state.BASE_URL}/api/v1/user/company-member`,
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
                            results: response.data.map((companyMember) => ({
                                id: companyMember.company_member?.uuid,
                                text: companyMember.company_member
                                    ?.company_name,
                            })),
                        };
                    },
                    cache: true,
                },
            });

            $(this.$refs.companyMember).on("change", () => {
                this.form.companyMemberId = $(this.$refs.companyMember).val();
                $(".select2-hidden-accessible").removeClass("is-invalid");
            });
        },
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", ""])
                .then((response) => {
                    this.welderSkills = response.data;
                })
                .catch((err) => {});
        },
        handleSubmit() {
            this.errors = {};
            this.isLoading = true;

            if (this.checkRoleAccess([this.$store.state.MEMBER_COMPANY])) {
                this.form.companyMemberId = this.user.companyMember.uuid;
            }

            this.$store
                .dispatch("postData", ["job-vacancy", this.form])
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
        checkRoleAccess(roles) {
            let access = false;
            if (roles) {
                roles.forEach((role) => {
                    if (this.roles.includes(role)) {
                        access = true;
                        this.getCompanyMembers();
                    }
                });
            }

            return access;
        },
    },
    components: { PageTitle, Success },
};
</script>

<template>
    <PageTitle title="Tambah Lowongan" :isBack="true" @onBack="onBack">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Job Vacancy' }"
                    >Lowongan Pekerjaan</router-link
                >
            </li>
            <li class="breadcrumb-item active">Tambah Lowongan</li>
        </ol>
    </PageTitle>

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div
                    class="mb-2"
                    v-if="
                        checkRoleAccess([
                            $store.state.ADMIN_APP,
                            $store.state.ADMIN_HUB,
                        ])
                    "
                >
                    <label>Pilih Perusahaan</label>
                    <select
                        class="form-select select2-hidden-accessible"
                        ref="companyMember"
                        :class="{ 'is-invalid': errors.companyMemberId }"
                        :disabled="isLoading"
                    ></select>
                    <div
                        class="invalid-feedback"
                        v-if="errors.companyMemberId"
                        v-for="(error, index) in errors.companyMemberId"
                        :key="index"
                    >
                        {{ error }}
                    </div>
                </div>
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
                        {{ error }}
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
                        {{ error }}
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
                        {{ error }}
                    </div>
                </div>
                <div class="mb-2">
                    <label>Perkiraan Gaji</label>
                    <input
                        type="number"
                        class="form-control"
                        min="1000"
                        v-model="form.salary"
                        :class="{ 'is-invalid': errors.salary }"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.salary"
                        v-for="(error, index) in errors.salary"
                        :key="index"
                    >
                        {{ error }}
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
                        {{ error }}
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
                        {{ error }}
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <router-link
                    :to="{ name: 'Job Vacancy' }"
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
        :url="{ name: 'Job Vacancy' }"
        :msg="'data berhasil ditambahkan.'"
    />
</template>
