<script>
import PageTitle from "../../components/PageTitle.vue";
import util from "../../store/utils/util";

export default {
    data() {
        return {
            isLoading: false,
            costWelderMember: {},
            costCompanyMember: {},
        };
    },
    mounted() {
        this.getCostWelderMember();
        this.getCostCompanyMember();
    },
    methods: {
        getCostWelderMember() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", [
                    "cost",
                    this.$store.state.COST.MEMBER_WELDER,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.costWelderMember = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getCostCompanyMember() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", [
                    "cost",
                    this.$store.state.COST.MEMBER_COMPANY,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.costCompanyMember = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getRupiah(amount) {
            return util.getRupiah(amount);
        },
    },
    components: { PageTitle },
};
</script>
<template>
    <div class="text-center mt-4 mb-3">
        <h3 class="mb-2">Daftar Member</h3>
        <p class="text-muted w-50 m-auto">
            Silahkan bergabung bersama kami untuk mengembangkan Pengelasan di
            Indonesia.
        </p>
    </div>

    <div class="row mb-3">
        <div class="col-md-5 offset-lg-1">
            <div class="card card-pricing">
                <div class="card-body text-center">
                    <p class="card-pricing-plan-name fw-bold text-uppercase">
                        Welder Member
                    </p>
                    <h2 class="card-pricing-price">
                        {{ getRupiah(costWelderMember.nominalPrice) }}
                        <span>/ Tahun</span>
                    </h2>
                    <ul class="card-pricing-features">
                        <li class="h5 my-0 py-0 fw-bold text-uppercase">
                            Keuntungan
                        </li>
                        <hr />
                        <li>Mendapatkan Informasi Terbaru Pengelasan</li>
                        <li>Mendapatkan Informasi Lowongan Pekerjaan</li>
                        <li>Mendapatkan Konsultasi bersama Pakar IWS</li>
                    </ul>
                    <router-link
                        :to="{ name: 'Member Welder' }"
                        class="btn btn-primary mt-4 mb-2 rounded-pill"
                    >
                        Pilih Member
                    </router-link>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-pricing">
                <div class="card-body text-center">
                    <p class="card-pricing-plan-name fw-bold text-uppercase">
                        Perusahaan Member
                    </p>
                    <h2 class="card-pricing-price">
                        {{ getRupiah(costCompanyMember.nominalPrice) }}
                        <span>/ Tahun</span>
                    </h2>
                    <ul class="card-pricing-features">
                        <li class="h5 my-0 py-0 fw-bold text-uppercase">
                            Keuntungan
                        </li>
                        <hr />
                        <li>Mendapatkan Informasi Terbaru Pengelasan</li>
                        <li>Mendapatkan Konsultasi bersama Pakar IWS</li>
                        <li>Menyajikan Berita terkait Perusahaan</li>
                    </ul>
                    <router-link
                        :to="{ name: 'Member Company' }"
                        class="btn btn-primary mt-4 mb-2 rounded-pill"
                    >
                        Pilih Member
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
