<script>
import Loader from "../../components/Loader.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import jsCookie from "js-cookie";

export default {
    props: ["slug"],
    data() {
        return {
            isLoading: false,
            form: {
                welderSkillIds: [],
                residentIdCard: "",
                dateBirth: "",
                birthPlace: "",
                citizenship: "",
                village: "",
                district: "",
                regency: "",
                province: "",
                zipCode: "",
                phone: "",
                workingStatus: "",
                documentPasPhoto: "",
                documentCurriculumVitae: "",
                documentCertificateCompetency: "",
            },
            isRegency: true,
            isDistrict: true,
            isVillage: true,
            errors: {},
            provinces: [],
            regencies: [],
            districts: [],
            villages: [],
        };
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
            formData.append("citizenship", this.form.citizenship);
            formData.append("village", this.form.village);
            formData.append("district", this.form.district);
            formData.append("regency", this.form.regency);
            formData.append("province", this.form.province);
            formData.append("zip_code", this.form.zipCode);
            formData.append("phone", this.form.phone);
            formData.append(
                "document_curriculum_vitae",
                this.form.documentCurriculumVitae
            );
            formData.append("working_status", this.form.workingStatus);
            formData.append("document_pas_photo", this.form.documentPasPhoto);
            formData.append(
                "document_certificate_competency",
                this.form.documentCertificateCompetency
            );

            return formData;
        },
    },
    mounted() {
        this.getProvince();
        this.getWelderSkills();
    },
    methods: {
        getProvince() {
            fetch(this.$store.state.BASE_URL_REGION + "provinces.json")
                .then((response) => response.json())
                .then((provinces) => {
                    this.provinces = provinces;
                });
        },
        getRegency(e) {
            fetch(
                `${this.$store.state.BASE_URL_REGION}regencies/${e.target.value}.json`
            )
                .then((response) => response.json())
                .then((regencies) => {
                    this.regencies = regencies;
                    this.$refs.regency.value = "";
                });
        },
        getDistrict(e) {
            fetch(
                `${this.$store.state.BASE_URL_REGION}districts/${e.target.value}.json`
            )
                .then((response) => response.json())
                .then((districts) => {
                    this.districts = districts;
                    this.$refs.district.value = "";
                });
        },
        getVillage(e) {
            fetch(
                `${this.$store.state.BASE_URL_REGION}villages/${e.target.value}.json`
            )
                .then((response) => response.json())
                .then((villages) => {
                    this.villages = villages;
                    this.$refs.village.value = "";
                });
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

            this.$store
                .dispatch("postDataUpload", [
                    "user/personal-data",
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
            this.$router.push({
                name: "Job Vacancy Detail",
                params: { slug: this.slug },
            });
        },
        onOk() {
            this.$emit("onSuccess", true);
        },
        uploadCurriculumVitae(e) {
            this.form.documentCurriculumVitae = e.target.files[0];
        },
        uploadPasPhoto(e) {
            this.form.documentPasPhoto = e.target.files[0];
        },
        uploadCertificateCompetency(e) {
            this.form.documentCertificateCompetency = e.target.files[0];
        },
    },
    components: { PageTitle, Loader, Success },
};
</script>

<template>
    <PageTitle title="Data Diri Pekerja" :isBack="true" @onBack="onBack()" />

    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>NIK</label>
                            <input
                                type="number"
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
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Status Bekerja</label>
                            <select
                                class="form-select"
                                v-model="form.workingStatus"
                                :class="{ 'is-invalid': errors.workingStatus }"
                                :disabled="isLoading"
                            >
                                <option disabled selected></option>
                                <option value="1">Sudah Pernah Bekerja</option>
                                <option value="0">Belum Pernah Bekerja</option>
                            </select>
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
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Jenis Kompetensi</label
                            ><select
                                class="select2-hidden-accessible form-select"
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
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Kewarganegaraan</label
                            ><select
                                class="form-select"
                                v-model="form.citizenship"
                                :class="{ 'is-invalid': errors.citizenship }"
                                :disabled="isLoading"
                            >
                                <option disabled selected></option>
                                <option value="WNI">
                                    Warga Negara Indonesia
                                </option>
                                <option value="WNA">Warga Negara Asing</option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.citizenship"
                                v-for="(error, index) in errors.citizenship"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Provinsi</label>
                            <select
                                class="form-select"
                                v-model="form.province"
                                :class="{ 'is-invalid': errors.province }"
                                :disabled="isLoading"
                                @change="
                                    (isRegency = false), getRegency($event)
                                "
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="(province, index) in provinces"
                                    :key="index"
                                    :value="province.id"
                                >
                                    {{ province.name }}
                                </option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.province"
                                v-for="(error, index) in errors.province"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Kota/Kabupaten</label>
                            <select
                                class="form-select"
                                v-model="form.regency"
                                :class="{ 'is-invalid': errors.regency }"
                                :disabled="isLoading || isRegency"
                                ref="regency"
                                @change="
                                    (isDistrict = false), getDistrict($event)
                                "
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="(regency, index) in regencies"
                                    :key="index"
                                    :value="regency.id"
                                >
                                    {{ regency.name }}
                                </option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.regency"
                                v-for="(error, index) in errors.regency"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Kecamatan</label>
                            <select
                                class="form-select"
                                v-model="form.district"
                                :class="{ 'is-invalid': errors.district }"
                                :disabled="isLoading || isDistrict"
                                ref="district"
                                @change="
                                    (isVillage = false), getVillage($event)
                                "
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="(district, index) in districts"
                                    :key="index"
                                    :value="district.id"
                                >
                                    {{ district.name }}
                                </option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.district"
                                v-for="(error, index) in errors.district"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Desa/Kelurahan</label>
                            <select
                                class="form-select"
                                ref="village"
                                v-model="form.village"
                                :class="{ 'is-invalid': errors.village }"
                                :disabled="isLoading || isVillage"
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="(village, index) in villages"
                                    :key="index"
                                    :value="village.id"
                                >
                                    {{ village.name }}
                                </option>
                            </select>
                            <div
                                class="invalid-feedback"
                                v-if="errors.village"
                                v-for="(error, index) in errors.village"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Kode Pos</label
                            ><input
                                type="text"
                                class="form-control"
                                v-model="form.zipCode"
                                :class="{ 'is-invalid': errors.zipCode }"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.zipCode"
                                v-for="(error, index) in errors.zipCode"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>No Telepon</label
                            ><input
                                type="number"
                                class="form-control"
                                v-model="form.phone"
                                :class="{ 'is-invalid': errors.phone }"
                                :disabled="isLoading"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.phone"
                                v-for="(error, index) in errors.phone"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Pas Foto Formal Berwarna</label
                            ><input
                                type="file"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.documentPasPhoto,
                                }"
                                :disabled="isLoading"
                                @change="uploadPasPhoto"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.documentPasPhoto"
                                v-for="(
                                    error, index
                                ) in errors.documentPasPhoto"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label
                                >Sertifikat Pelatihan/Kompetensi Keahlian
                                Pengelasan </label
                            ><input
                                type="file"
                                class="form-control"
                                :class="{
                                    'is-invalid':
                                        errors.documentCertificateCompetency,
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
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Daftar Riwayat Hidup</label
                            ><input
                                type="file"
                                class="form-control"
                                :class="{
                                    'is-invalid':
                                        errors.documentCurriculumVitae,
                                }"
                                :disabled="isLoading"
                                @change="uploadCurriculumVitae"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.documentCurriculumVitae"
                                v-for="(
                                    error, index
                                ) in errors.documentCurriculumVitae"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <router-link
                    :to="{ name: 'Job Vacancy', params: { uuid: uuid } }"
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

    <Success :msg="'data berhasil disimpan.'" />
</template>
