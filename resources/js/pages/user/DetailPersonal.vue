<script>
import util from "../../store/utils/util";
import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    props: ["registerJob"],
    data() {
        return {
            village: "",
            district: "",
            regency: "",
            province: "",
        };
    },
    watch: {},
    methods: {
        getVillage(id) {
            fetch(`${this.$store.state.BASE_URL_REGION}village/${id}.json`)
                .then((response) => response.json())
                .then((village) => {
                    this.village = util.convertToCapitalize(village.name);
                });

            return this.village;
        },
        getDistrict(id) {
            fetch(`${this.$store.state.BASE_URL_REGION}district/${id}.json`)
                .then((response) => response.json())
                .then((district) => {
                    this.district = util.convertToCapitalize(district.name);
                });

            return this.district;
        },
        getRegency(id) {
            fetch(`${this.$store.state.BASE_URL_REGION}regency/${id}.json`)
                .then((response) => response.json())
                .then((regency) => {
                    this.regency = util.convertToCapitalize(regency.name);
                });

            return this.regency;
        },
        getProvince(id) {
            fetch(`${this.$store.state.BASE_URL_REGION}province/${id}.json`)
                .then((response) => response.json())
                .then((province) => {
                    this.province = util.convertToCapitalize(province.name);
                });

            return this.province;
        },
        getCitizenship(citizenship) {
            return util.getCitizenship(citizenship);
        },
        getSkill(skill) {
            return util.convertToCapitalize(skill);
        },
        getDateBirth(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        onRegister() {
            this.$emit("onRegister", true);
        },
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <center>
                        <img
                            :src="registerJob.detailWorker?.pasPhoto"
                            :alt="registerJob.name"
                            style="width: 3cm; height: 4cm"
                        />
                    </center>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th class="p-2 border bg-primary">Nama</th>
                                <td class="p-2 border">
                                    {{ registerJob.name }}
                                </td>
                            </tr>
                            <tr>
                                <th class="p-2 border bg-primary">No. KTP</th>
                                <td class="p-2 border">
                                    {{
                                        registerJob.detailWorker?.residentIdCard
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <th class="p-2 border bg-primary">
                                    Tempat, Tanggal Lahir
                                </th>
                                <td class="p-2 border">
                                    {{
                                        `${
                                            registerJob.detailWorker?.birthPlace
                                        }, ${getDateBirth(
                                            registerJob.detailWorker?.dateBirth
                                        )}`
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <th class="p-2 border bg-primary">Alamat</th>
                                <td class="p-2 border">
                                    {{
                                        `desa ${getVillage(
                                            registerJob.personalData?.village
                                        )}, kec. ${getDistrict(
                                            registerJob.personalData?.district
                                        )}, kab. ${getRegency(
                                            registerJob.personalData?.regency
                                        )}, ${getProvince(
                                            registerJob.personalData?.province
                                        )}, ${
                                            registerJob.personalData?.zipCode
                                        }`
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <th class="p-2 border bg-primary">
                                    Kewarganegaraan
                                </th>
                                <td class="p-2 border">
                                    {{
                                        getCitizenship(
                                            registerJob.personalData
                                                ?.citizenship
                                        )
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <th class="p-2 border bg-primary">
                                    Keahlian Pelamar
                                </th>
                                <td class="p-2 border">
                                    <ul>
                                        <li
                                            v-for="(
                                                welderSkill, index
                                            ) in registerJob.welderHasSkills"
                                            :key="index"
                                        >
                                            {{
                                                getSkill(
                                                    welderSkill.welderSkill
                                                        ?.skillName
                                                )
                                            }}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th class="p-2 border bg-primary">
                                    Dokumen Pendukung
                                </th>
                                <td class="p-2 border">
                                    <ul>
                                        <li>
                                            <a
                                                :href="
                                                    registerJob.personalData
                                                        ?.curriculumVitae
                                                "
                                                target="_blank"
                                                >CV</a
                                            >
                                        </li>
                                        <li
                                            v-for="(
                                                certificate, index
                                            ) in registerJob.welderDocuments"
                                            :key="index"
                                        >
                                            <a
                                                :href="certificate.documentPath"
                                                target="_blank"
                                                >Sertifikat Keahlian
                                                {{ index + 1 }}</a
                                            >
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <router-link
                :to="{ name: 'Member' }"
                class="btn btn-sm btn-secondary"
                >Batal</router-link
            >
            <button class="btn btn-sm btn-primary" @click="onRegister()">
                Daftar
            </button>
        </div>
    </div>
</template>

<style scoped>
th.bg-primary {
    background-color: #cfe2ff !important;
}
</style>
