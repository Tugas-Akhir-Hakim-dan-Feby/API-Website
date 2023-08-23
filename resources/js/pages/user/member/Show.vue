<script>
import PageTitle from "../../../components/PageTitle.vue";
import dayjs from "dayjs";
import "dayjs/locale/id";
import Loader from "../../../components/Loader.vue";

export default {
    props: ["id"],
    data() {
        return {
            user: {},
            isLoading: false,
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        getUser() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["user/welder-member", this.id])
                .then((response) => {
                    this.isLoading = false;

                    this.user = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getDateBirth(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        getCreatedAt(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        onBack() {
            this.$router.back();
        },
        checkFile(file) {
            let url = window.location.origin + "/storage";

            if (file && file.length > url.length) {
                return true;
            }
            return false;
        },
    },
    components: { PageTitle, Loader },
};
</script>
<template>
    <PageTitle
        :title="'Detail Personal Member'"
        :isBack="true"
        @onBack="onBack"
    >
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'User Member' }"
                    >Personal Member</router-link
                >
            </li>
            <li class="breadcrumb-item active">Detail Welder</li>
        </ol>
    </PageTitle>

    <div class="card">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5">
                    <img
                        :src="
                            user.welderMember
                                ? user.welderMember?.pasPhoto
                                : `https://ui-avatars.com/api/?background=random&size=280&rounded=false&length=2&name=${user.name}`
                        "
                        class="img-fluid"
                        style="max-width: 280px"
                        :alt="user.name"
                        onerror="this.src=null; this.src='https://www.salonlfc.com/wp-content/uploads/2018/01/image-not-found-scaled.png'"
                    />
                </div>
                <div class="col-lg-7">
                    <form class="ps-lg-4">
                        <h3 class="mt-0" v-html="user.name"></h3>
                        <p class="mb-1">
                            Terdaftar:
                            {{ getCreatedAt(user.welderMember?.createdAt) }}
                        </p>
                        <div class="mb-3">
                            <h6 class="font-14">Email Pengguna:</h6>
                            <p v-html="user.name"></p>
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <h6 class="font-14">Keahlian:</h6>
                                    <ul>
                                        <li
                                            v-for="(
                                                skill, index
                                            ) in user.welderHasSkills"
                                            :key="index"
                                            v-html="skill.welderSkill.skillName"
                                        ></li>
                                    </ul>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="font-14">
                                        Tempat, Tanggal Lahir
                                    </h6>
                                    <p
                                        v-html="
                                            `${
                                                user.welderMember?.birthPlace
                                            }, ${getDateBirth(
                                                user.welderMember?.dateBirth
                                            )}`
                                        "
                                    ></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-centered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pas Foto Formal Berwarna</td>
                            <td>
                                <a
                                    :href="user.welderMember?.pasPhoto"
                                    target="_blank"
                                    v-if="checkFile(user.expert?.pasPhoto)"
                                    ><i class="mdi mdi-download"></i> Unduh</a
                                >
                                <p v-else>belum tersedia</p>
                            </td>
                        </tr>
                        <tr
                            v-for="(
                                welderDocument, index
                            ) in user.welderDocuments"
                            :key="index"
                        >
                            <td>Sertifikat Kompetensi Pengelasan Lainnya</td>
                            <td>
                                <a
                                    :href="welderDocument.documentPath"
                                    target="_blank"
                                    ><i class="mdi mdi-download"></i> Unduh</a
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
