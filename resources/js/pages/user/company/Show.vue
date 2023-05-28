<script>
import PageTitle from "../../../components/PageTitle.vue";

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
                .dispatch("showData", ["user/company-member", this.id])
                .then((response) => {
                    this.user = response.data;
                })
                .catch((error) => {});
        },
        onBack() {
            this.$router.back();
        },
    },
    components: { PageTitle },
};
</script>
<template>
    <PageTitle
        :title="'Detail Pengguna Member Perusahaan'"
        :isBack="true"
        @onBack="onBack"
    />

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <form class="ps-lg-4">
                        <h3
                            class="mt-0"
                            v-html="user.companyMember?.companyName"
                        ></h3>
                        <p class="mb-1">
                            Terdaftar: {{ user.companyMember?.createdAt }}
                        </p>
                        <div class="mt-3">
                            <h6 class="font-14">Pimpinan Perusahaan:</h6>
                            <p v-html="user.companyMember?.companyDirector"></p>
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="font-14">Asal Perusahaan:</h6>
                                    <p
                                        v-html="
                                            user.companyMember?.companyAddress
                                        "
                                    ></p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="font-14">No Telepon</h6>
                                    <p v-html="user.companyMember?.phone"></p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="font-14">No Fax</h6>
                                    <p
                                        v-html="user.companyMember?.facsmile"
                                    ></p>
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
                        <p v-html="user.name"></p>
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
