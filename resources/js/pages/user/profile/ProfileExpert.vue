<script>
import Success from "../../../components/notifications/Success.vue";
import iziToast from "izitoast";
import dayjs from "dayjs";
import "dayjs/locale/id";
import UploadFileExpert from "./uploadFile/UploadFileExpert.vue";

export default {
    data() {
        return {
            form: {},
            errors: {},
            document: {},
            welderSkills: [],
            isLoading: false,
            users: {},
            provinces: [],
            regencies: [],
            districts: [],
            villages: [],
            isRegency: true,
            isDistrict: true,
            isVillage: true,
        };
    },
    mounted() {
        this.getUser();
        this.getProvince();
        this.getWelderSkills();
    },
    methods: {
        setForm(user) {
            this.form = {
                name: user.name,
                email: user.email,
                welderSkillId: user.welderMember?.welderSkill?.uuid,
                residentIdCard: user.welderMember?.residentIdCard,
                dateBirth: this.getDateBirth(user.welderMember?.dateBirth),
                birthPlace: user.welderMember?.birthPlace,
                workingStatus: user.welderMember?.workingStatus,
                province: user.personalData?.province?.id,
                regency: user.personalData?.regency?.id,
                district: user.personalData?.district?.id,
                village: user.personalData?.village?.id,
                citizenship: user.personalData?.citizenship,
                zipCode: user.personalData?.zipCode,
                phone: user.personalData?.phone,
                instance: user.expert?.instance,
                id: user.uuid,
            };

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
                    this.getRegency({
                        target: {
                            value: response.user.personalData?.province?.id,
                        },
                    });
                    this.getDistrict({
                        target: {
                            value: response.user.personalData?.regency?.id,
                        },
                    });
                    this.getVillage({
                        target: {
                            value: response.user.personalData?.district?.id,
                        },
                    });
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getDateBirth(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
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
                });
        },
        getDistrict(e) {
            fetch(
                `${this.$store.state.BASE_URL_REGION}districts/${e.target.value}.json`
            )
                .then((response) => response.json())
                .then((districts) => {
                    this.districts = districts;
                });
        },
        getVillage(e) {
            fetch(
                `${this.$store.state.BASE_URL_REGION}villages/${e.target.value}.json`
            )
                .then((response) => response.json())
                .then((villages) => {
                    this.villages = villages;
                });
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", [
                    "user/expert",
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
    components: { Success, UploadFileExpert },
};
</script>
<template>
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>Informasi Pakar</h4>
            <p>Perbaharui informasi pakar Anda.</p>
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
                            <label class="col-sm-3">Asal Instansi</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.instance"
                                    :class="{ 'is-invalid': errors.instance }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.instance"
                                    v-for="(error, index) in errors.instance"
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

                        <div class="row mb-3">
                            <label class="col-sm-3">Nama Lengkap</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': errors.name,
                                    }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.name"
                                    v-for="(error, index) in errors.name"
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
                                    {{ error }}
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
                                    {{ error }}
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
                                    {{ error }}
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
                                    {{ error }}
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Jenis Kompetensi</label>
                            <div class="col">
                                <select
                                    class="form-select"
                                    v-model="form.welderSkillId"
                                    :class="{
                                        'is-invalid': errors.welderSkillId,
                                    }"
                                    :disabled="isLoading"
                                >
                                    <option disabled selected></option>
                                    <option
                                        v-for="(
                                            welderSkill, index
                                        ) in welderSkills"
                                        :key="index"
                                        :value="welderSkill.uuid"
                                        v-html="welderSkill.skillName"
                                    ></option>
                                </select>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.welderSkillId"
                                    v-for="(
                                        error, index
                                    ) in errors.welderSkillId"
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
                                    <option value="WNI">
                                        Warga Negara Indonesia
                                    </option>
                                    <option value="WNA">
                                        Warga Negara Asing
                                    </option>
                                </select>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.citizenship"
                                    v-for="(error, index) in errors.citizenship"
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
                        <div class="row mb-3">
                            <label class="col-sm-3">Provinsi</label>
                            <div class="col">
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
                                    @change="
                                        (isDistrict = false),
                                            getDistrict($event)
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
                                    v-if="errors.village"
                                    v-for="(error, index) in errors.village"
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
                        <div class="row mb-3">
                            <label class="col-sm-3">Kode Pos</label>
                            <div class="col">
                                <input
                                    type="number"
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
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">No Telepon</label>
                            <div class="col">
                                <input
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
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
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

    <UploadFileExpert :document="document" />
</template>
