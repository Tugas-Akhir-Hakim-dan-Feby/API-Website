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
                .dispatch("showData", ["user/operator", this.id])
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
    },
    components: { PageTitle, Loader },
};
</script>

<template>
    <PageTitle
        :title="'Detail Pengguna Member TUK'"
        :isBack="true"
        @onBack="onBack"
    >
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'User Operator' }"
                    >Member TUK</router-link
                >
            </li>
            <li class="breadcrumb-item active">Detail TUK</li>
        </ol>
    </PageTitle>

    <div class="card">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5">
                    <img
                        :src="
                            user.operator?.logo
                                ? user.operator?.logo?.documentPath
                                : `https://ui-avatars.com/api/?background=random&size=280&rounded=false&length=2&name=${user.operator?.tukName}`
                        "
                        class="img-fluid"
                        style="max-width: 280px"
                        :alt="user.operator?.tukName"
                        onerror="this.src=null; this.src='https://www.salonlfc.com/wp-content/uploads/2018/01/image-not-found-scaled.png'"
                    />
                </div>
                <div class="col-lg-7">
                    <form class="ps-lg-4">
                        <h3 class="mt-0" v-html="user.name"></h3>
                        <p class="mb-1">
                            Terdaftar:
                            {{ getCreatedAt(user.operator?.createdAt) }}
                        </p>
                        <div class="mb-3">
                            <h6 class="font-14">Nama TUK:</h6>
                            <p v-html="user.operator?.tukName"></p>
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <h6 class="font-14">Kode TUK:</h6>
                                    <p v-html="user.operator?.tukCode"></p>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="font-14">Alamat TUK</h6>
                                    <p v-html="user.operator?.address"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <h6 class="font-14">No. Telepon:</h6>
                                    <p v-html="user.operator?.phone"></p>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="font-14">No. Fax</h6>
                                    <p v-html="user.operator?.facsmile"></p>
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
                            <th>Kegiatan Uji Kompetensi</th>
                            <th>Tanggal Pelaksanaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(examPacket, index) in user.operator
                                ?.examPackets"
                            :key="index"
                        >
                            <td
                                v-html="examPacket.competenceSchema?.skillName"
                            ></td>
                            <td
                                v-html="getCreatedAt(examPacket.examSchedule)"
                            ></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
