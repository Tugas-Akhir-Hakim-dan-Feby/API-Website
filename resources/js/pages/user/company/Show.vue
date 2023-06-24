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
                .dispatch("showData", ["user/company-member", this.id])
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
        :title="'Detail Pengguna Member Perusahaan'"
        :isBack="true"
        @onBack="onBack"
    >
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'User Company' }"
                    >Member Perusahaan</router-link
                >
            </li>
            <li class="breadcrumb-item active">Detail Perusahaan</li>
        </ol>
    </PageTitle>

    <div class="card">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <form class="ps-lg-4">
                        <h3
                            class="mt-0"
                            v-html="user.companyMember?.companyName"
                        ></h3>
                        <p class="mb-1">
                            Terdaftar:
                            {{ getCreatedAt(user.companyMember?.createdAt) }}
                        </p>
                        <div class="mt-3">
                            <h6 class="font-14">Pimpinan Perusahaan:</h6>
                            <p v-html="user.companyMember?.companyDirector"></p>
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="font-14">Alamat Perusahaan:</h6>
                                    <p
                                        v-html="
                                            user.companyMember?.companyAddress
                                        "
                                    ></p>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <h6 class="font-14">No Telepon</h6>
                                        <p
                                            v-html="user.companyMember?.phone"
                                        ></p>
                                    </div>
                                    <div>
                                        <h6 class="font-14">No Fax</h6>
                                        <p
                                            v-html="
                                                user.companyMember?.facsmile
                                            "
                                        ></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5">
                    <div class="mb-3">
                        <h6 class="font-14">Nama Pengguna:</h6>
                        <p v-html="user.name"></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-14">Email Pengguna:</h6>
                        <p v-html="user.email"></p>
                    </div>
                </div>
            </div>
            <div class="row mt-4 mx-lg-3">
                <h4>Profil Perusahaan</h4>
                <p v-html="user.companyMember?.companyProfile"></p>
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
                            <td>Legalitas Perusahaan</td>
                            <td>
                                <a
                                    target="_blank"
                                    :href="user.companyMember?.companyLegality"
                                    v-if="
                                        checkFile(
                                            user.companyMember?.companyLegality
                                        )
                                    "
                                    ><i class="mdi mdi-download"></i> Unduh</a
                                >
                                <p v-else>belum tersedia</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
