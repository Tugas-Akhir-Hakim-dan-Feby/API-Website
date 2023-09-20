<script>
import Success from "../../../components/notifications/Success.vue";
import iziToast from "izitoast";
import dayjs from "dayjs";
import "dayjs/locale/id";
import UploadFileWelderMember from "./uploadFile/UploadFileWelderMember.vue";
import Multiselect from "vue-multiselect";
import jsCookie from "js-cookie";
import { InputRowField } from "../../../components";

export default {
    data() {
        return {
            form: {},
            errors: {},
            document: {},
            welderSkills: [],
            provinces: [],
            regencies: [],
            districts: [],
            villages: [],
            citizenships: [
                {
                    id: "WNI",
                    name: "Warga Negara Indonesia",
                },
                {
                    id: "WNA",
                    name: "Warga Negara Asing",
                },
            ],
            isLoading: false,
            isMember: false,
        };
    },
    watch: {
        isMember(newVal) {
            if (this.isMember) {
                setTimeout(() => {
                    this.getWelderSkills();
                }, 1000);
            }
        },
    },
    mounted() {
        this.getUser();
        this.getProvince();
        if (this.isMember) {
            setTimeout(() => {
                this.getWelderSkills();
            }, 1000);
        }
    },
    methods: {
        setForm(user) {
            this.form.name = user.name;
            this.form.email = user.email;
            this.form.id = user.uuid;

            this.form.welderHasSkills = user.welderHasSkills;

            this.isMember = user.welderMember ?? false;

            if (user.welderMember) {
                setTimeout(() => {
                    this.form.residentIdCard =
                        user.welderMember?.residentIdCard;
                    this.form.dateBirth = this.getDateBirth(
                        user.welderMember?.dateBirth
                    );
                    this.form.birthPlace = user.welderMember?.birthPlace;
                    this.form.workingStatus = user.welderMember?.workingStatus;
                    this.form.province = user.personalData?.province?.id;
                    this.form.regency = user.personalData?.regency?.id;
                    this.form.district = user.personalData?.district?.id;
                    this.form.village = user.personalData?.village?.id;
                    this.form.zipCode = user.personalData?.zipCode;
                    this.form.phone = user.personalData?.phone;
                    this.form.citizenship = user.personalData?.citizenship;

                    this.getRegency({
                        target: { value: user.personalData?.province?.id },
                    });
                    this.getDistrict({
                        target: { value: user.personalData?.regency?.id },
                    });
                    this.getVillage({
                        target: { value: user.personalData?.district?.id },
                    });
                }, 1000);
            }

            this.document = {
                id: user.uuid,
                curriculumVitae: user.personalData?.curriculumVitae,
                pasPhoto: user.welderMember?.pasPhoto,
                competencyCertificates: user.welderDocuments,
            };
        },
        async getUser() {
            this.isLoading = true;
            await this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.isLoading = false;
                    this.setForm(response.user);
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.getUser();
                });
        },
        getDateBirth(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
        async getWelderSkills() {
            let select2 = $(this.$refs.welderSkill);
            await select2.select2({
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

            select2
                .val("04ba5888-22c5-4123-8042-c769e6a905ee")
                .trigger("change");

            select2.on("change", () => {
                this.form.welderSkillIds = select2.val();
                $(".select2-hidden-accessible").removeClass("is-invalid");
            });
        },
        async getProvince() {
            await this.$store
                .dispatch("getData", ["province", ""])
                .then((response) => {
                    this.provinces = response.data;
                })
                .catch((err) => {});
        },
        async getRegency(e, value = false) {
            const params = [`province_id=${e.target.value}`].join("&");

            if (value) {
                this.form.regency = null;
                this.form.district = null;
                this.form.village = null;
            }

            await this.$store
                .dispatch("getData", ["regency", params])
                .then((response) => {
                    this.regencies = response.data;
                })
                .catch((err) => {});
        },
        async getDistrict(e, value = false) {
            const params = [`regency_id=${e.target.value}`].join("&");

            if (value) {
                this.form.district = null;
                this.form.village = null;
            }

            await this.$store
                .dispatch("getData", ["district", params])
                .then((response) => {
                    this.districts = response.data;
                })
                .catch((err) => {});
        },
        async getVillage(e, value = false) {
            const params = [`district_id=${e.target.value}`].join("&");

            if (value) {
                this.form.village = null;
            }

            await this.$store
                .dispatch("getData", ["village", params])
                .then((response) => {
                    this.villages = response.data;
                })
                .catch((err) => {});
        },
        handleSubmit() {
            if (this.isMember) {
                this.handleSubmitMember();
            } else {
                this.handleSubmitAccount();
            }
        },
        handleSubmitMember() {
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
                    this.form.welderSkillIds = [];
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
        handleSubmitAccount() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", [
                    "user/admin-app",
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
        handleDeleteSkill(skillId) {
            this.isLoading = true;
            this.$store
                .dispatch("deleteData", [
                    "user/welder-member/delete-skill",
                    skillId,
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
                });
        },
        onSuccessUploadDocument(e) {
            this.getUser();
            iziToast.success({
                title: "Selamat",
                message: "data anda berhasil diperbaharui",
                position: "topCenter",
            });
        },
    },
    components: { Success, UploadFileWelderMember, Multiselect, InputRowField },
};
</script>
<template>
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>
                <span v-if="isMember">Informasi Member</span>
                <span v-else>Informasi Akun</span>
            </h4>
            <p>
                Perbaharui <span v-if="isMember">informasi member</span>
                <span v-else>informasi akun</span> Anda.
            </p>
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

                        <input-row-field
                            length="sm-3"
                            label="Nama Lengkap"
                            :isLoading="isLoading"
                            @update:value="form.name = $event"
                            :value="form.name"
                            :errors="errors.name"
                        />
                        <input-row-field
                            length="sm-3"
                            label="Email"
                            :isLoading="isLoading"
                            @update:value="form.email = $event"
                            :value="form.email"
                            :errors="errors.email"
                        />
                        <input-row-field
                            v-if="isMember"
                            length="sm-3"
                            label="NIK"
                            :isLoading="isLoading"
                            @update:value="form.residentIdCard = $event"
                            :value="form.residentIdCard"
                            :errors="errors.residentIdCard"
                        />
                        <input-row-field
                            length="sm-3"
                            label="Telepon"
                            :isLoading="isLoading"
                            @update:value="form.phone = $event"
                            :value="form.phone"
                            :errors="errors.phone"
                            type="number"
                        />
                        <input-row-field
                            v-if="isMember"
                            length="sm-3"
                            label="Tempat Lahir"
                            :isLoading="isLoading"
                            @update:value="form.birthPlace = $event"
                            :value="form.birthPlace"
                            :errors="errors.birthPlace"
                        />
                        <input-row-field
                            v-if="isMember"
                            length="sm-3"
                            label="Tanggal Lahir"
                            :isLoading="isLoading"
                            @update:value="form.dateBirth = $event"
                            :value="form.dateBirth"
                            :errors="errors.dateBirth"
                            type="date"
                        />
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
                                <ul
                                    style="padding-left: 1.2rem; margin-top: 0"
                                    id="listSkill"
                                >
                                    <li
                                        v-for="(
                                            welderSkill, index
                                        ) in form.welderHasSkills"
                                        :key="index"
                                    >
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                        >
                                            {{
                                                welderSkill.welderSkill
                                                    .skillName
                                            }}
                                            <span
                                                v-if="
                                                    form.welderHasSkills
                                                        .length > 1
                                                "
                                                style="
                                                    font-size: 1.4em;
                                                    cursor: pointer;
                                                "
                                                class="text-danger"
                                                @click="
                                                    handleDeleteSkill(
                                                        welderSkill.welderSkill
                                                            .uuid
                                                    )
                                                "
                                            >
                                                &times;
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Kewarganegaraan</label>
                            <div class="col">
                                <select
                                    class="form-select"
                                    v-model="form.citizenship"
                                    :class="{
                                        'is-invalid': errors.citizenship,
                                    }"
                                    :disabled="isLoading"
                                >
                                    <option disabled selected></option>
                                    <option
                                        v-for="(
                                            citizenship, index
                                        ) in citizenships"
                                        :key="index"
                                        :value="citizenship.id"
                                    >
                                        {{ citizenship.name }}
                                    </option>
                                </select>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.province"
                                    v-for="(error, index) in errors.province"
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
                            <label class="col-sm-3">Provinsi</label>
                            <div class="col">
                                <select
                                    class="form-select"
                                    v-model="form.province"
                                    :class="{ 'is-invalid': errors.province }"
                                    :disabled="isLoading"
                                    ref="province"
                                    @change="getRegency($event, true)"
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
                            <label class="col-sm-3">Kota/Kabupaten</label>
                            <div class="col">
                                <select
                                    class="form-select"
                                    v-model="form.regency"
                                    :class="{ 'is-invalid': errors.regency }"
                                    :disabled="isLoading"
                                    ref="regency"
                                    @change="getDistrict($event, true)"
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
                            <label class="col-sm-3">Kecamatan</label>
                            <div class="col">
                                <select
                                    class="form-select"
                                    v-model="form.district"
                                    :class="{ 'is-invalid': errors.district }"
                                    :disabled="isLoading"
                                    ref="district"
                                    @change="getVillage($event, true)"
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
                            <label class="col-sm-3">Desa/Kelurahan</label>
                            <div class="col">
                                <select
                                    class="form-select"
                                    v-model="form.village"
                                    ref="village"
                                    :class="{ 'is-invalid': errors.village }"
                                    :disabled="isLoading"
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
                                    v-if="errors.district"
                                    v-for="(error, index) in errors.district"
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
                        <input-row-field
                            length="sm-3"
                            label="Kode Pos"
                            :isLoading="isLoading"
                            @update:value="form.zipCode = $event"
                            :value="form.zipCode"
                            :errors="errors.zipCode"
                            type="number"
                        />
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-primary btn-sm"
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

    <UploadFileWelderMember
        :document="document"
        @onSuccess="onSuccessUploadDocument()"
    />
</template>

<style scoped>
#listSkill li:hover {
    color: blue;
}
</style>
