<script>
import PageTitle from "../../../components/PageTitle.vue";
import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    props: ["id"],
    data() {
        return {
            user: {},
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        getUser() {
            this.$store
                .dispatch("showData", ["user/expert", this.id])
                .then((response) => {
                    this.user = response.data;
                })
                .catch((error) => {});
        },
        getCreatedAt(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        getDateBirth(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        onBack() {
            this.$router.push({ name: "User Expert" });
        },
    },
    components: { PageTitle },
};
</script>

<template>
    <PageTitle
        :title="'Detail Pakar ' + user.name"
        :isBack="true"
        @onBack="onBack"
    />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <a
                                href="javascript: void(0);"
                                class="text-center d-block mb-4"
                            >
                                <img
                                    :src="
                                        user.welderMember
                                            ? user.welderMember?.pasPhoto
                                            : `https://ui-avatars.com/api/?background=random&size=280&rounded=false&length=2&name=${user.name}`
                                    "
                                    class="img-fluid"
                                    style="max-width: 280px"
                                    :alt="user.name"
                                />
                            </a>

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
                                    <th>Aksi</th>
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
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
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
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
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
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
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
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
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
                                            ><i class="mdi mdi-download"></i>
                                            Unduh</a
                                        >
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
