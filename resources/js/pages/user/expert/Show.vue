<script>
import PageTitle from "../../../components/PageTitle.vue";
import dayjs from "dayjs";
import "dayjs/locale/id";
import Loader from "../../../components/Loader.vue";

export default {
    props: ["id"],
    data() {
        return {
            isLoading: false,
            user: {},
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        getUser() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["user/expert", this.id])
                .then((response) => {
                    this.isLoading = false;
                    this.user = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getCreatedAt(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        getDateBirth(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        checkFile(file) {
            let url = window.location.origin + "/storage";

            if (file && file.length > url.length) {
                return true;
            }
            return false;
        },
        onBack() {
            this.$router.push({ name: "User Expert" });
        },
    },
    components: { PageTitle, Loader },
};
</script>

<template>
    <PageTitle
        :title="'Detail Pakar ' + user.name"
        :isBack="true"
        @onBack="onBack"
    >
        <div class="d-flex">
            <button
                class="btn btn-sm btn-primary mb-1 me-2"
                v-if="user.expert?.status == 'NOT-APPROVED'"
            >
                Konfirmasi
            </button>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item">
                    <router-link :to="{ name: 'Dashboard' }"
                        >Dashboard</router-link
                    >
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{ name: 'User Expert' }"
                        >Pakar</router-link
                    >
                </li>
                <li class="breadcrumb-item active">Detail Pakar</li>
            </ol>
        </div>
    </PageTitle>

    <div class="row">
        <div class="col-12 position-relative">
            <Loader v-if="isLoading" />
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="text-center d-block mb-4">
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
                            <div
                                class="d-lg-flex d-none justify-content-center"
                            ></div>
                        </div>
                        <div class="col-lg-7">
                            <form class="ps-lg-4">
                                <h3 class="mt-0" v-html="user.name"></h3>
                                <p class="mb-1">
                                    Terdaftar:
                                    {{ getCreatedAt(user.expert?.createdAt) }}
                                </p>

                                <div class="mt-3">
                                    <h6 class="font-14">NIK:</h6>
                                    <p
                                        v-html="
                                            user.welderMember?.residentIdCard
                                        "
                                    ></p>
                                </div>

                                <div class="mt-3">
                                    <h6 class="font-14">Email:</h6>
                                    <p v-html="user.email"></p>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="font-14">
                                                Asal Instansi:
                                            </h6>
                                            <p
                                                v-html="user.expert?.instance"
                                            ></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="font-14">
                                                Tempat, Tanggal Lahir:
                                            </h6>
                                            <p
                                                v-html="
                                                    user.welderMember
                                                        ?.birthPlace +
                                                    ', ' +
                                                    getDateBirth(
                                                        user.welderMember
                                                            ?.dateBirth
                                                    )
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Sertifikat Kompetensi Bidang Pengelasan
                                        Tingkat Nasional atau International
                                    </td>
                                    <td>
                                        <a
                                            :href="
                                                user.expert
                                                    ?.certificateCompetency
                                            "
                                            target="_blank"
                                            v-if="
                                                checkFile(
                                                    user.expert
                                                        ?.certificateCompetency
                                                )
                                            "
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
                                        <p v-else>belum tersedia</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Riwayat Pengalaman Bekerja atau
                                        Penelitian Bidang Pengelasan 10 Tahun
                                        Terakhir
                                    </td>
                                    <td>
                                        <a
                                            :href="user.expert?.career"
                                            target="_blank"
                                            v-if="
                                                checkFile(user.expert?.career)
                                            "
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
                                        <p v-else>belum tersedia</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Surat Keterangan Aktif Bekerja Dalam
                                        Bidang Pengelasan
                                    </td>
                                    <td>
                                        <a
                                            :href="user.expert?.workingMail"
                                            target="_blank"
                                            v-if="
                                                checkFile(
                                                    user.expert?.workingMail
                                                )
                                            "
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
                                        <p v-else>belum tersedia</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Memiliki Sertifikat atau Ijazah Gelar
                                        Profesi
                                    </td>
                                    <td>
                                        <a
                                            :href="
                                                user.expert
                                                    ?.certificateProfession
                                            "
                                            target="_blank"
                                            v-if="
                                                checkFile(
                                                    user.expert
                                                        ?.certificateProfession
                                                )
                                            "
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
                                        <p v-else>belum tersedia</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ijazah Pendidikan Formal</td>
                                    <td>
                                        <a
                                            :href="
                                                user.welderMember
                                                    ?.certificateSchool
                                            "
                                            target="_blank"
                                            v-if="
                                                checkFile(
                                                    user.expert
                                                        ?.certificateSchool
                                                )
                                            "
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
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
                                    <td>
                                        Sertifikat Kompetensi Pengelasan Lainnya
                                    </td>
                                    <td>
                                        <a
                                            :href="welderDocument.documentPath"
                                            target="_blank"
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
