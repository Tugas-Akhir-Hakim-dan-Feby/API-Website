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
    },
    components: { PageTitle, Loader },
};
</script>
<template>
    <PageTitle
        :title="'Detail Pengguna Welder Member'"
        :isBack="true"
        @onBack="onBack"
    />
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
                                    <p
                                        v-html="
                                            user.welderMember?.welderSkill
                                                ?.skillName
                                        "
                                    ></p>
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
