<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";
import util from "../../store/utils/util";

export default {
    data() {
        return {
            isLoadingWelder: false,
            isLoadingCompany: false,
            isLoadingTuk: false,
            costWelderMember: {},
            costCompanyMember: {},
            costTuk: {},
        };
    },
    mounted() {
        this.getCostTuk();
        this.getCostWelderMember();
        this.getCostCompanyMember();

        let isPayment = JSON.parse(localStorage.getItem("isPayment"));
        if (isPayment) {
            window.location.href =
                "/invoice/" +
                isPayment.externalId +
                "/" +
                isPayment.paymentType;
        }
    },
    methods: {
        getCostWelderMember() {
            this.isLoadingWelder = true;
            this.$store
                .dispatch("showData", [
                    "cost",
                    this.$store.state.COST.MEMBER_WELDER,
                ])
                .then((response) => {
                    this.isLoadingWelder = false;
                    this.costWelderMember = response.data;
                })
                .catch((error) => {
                    this.isLoadingWelder = false;
                });
        },
        getCostCompanyMember() {
            this.isLoadingCompany = true;
            this.$store
                .dispatch("showData", [
                    "cost",
                    this.$store.state.COST.MEMBER_COMPANY,
                ])
                .then((response) => {
                    this.isLoadingCompany = false;
                    this.costCompanyMember = response.data;
                })
                .catch((error) => {
                    this.isLoadingCompany = false;
                });
        },
        getCostTuk() {
            this.isLoadingTuk = true;
            this.$store
                .dispatch("showData", [
                    "cost",
                    this.$store.state.COST.EXAM_INSTITUTE,
                ])
                .then((response) => {
                    this.isLoadingTuk = false;
                    this.costTuk = response.data;
                })
                .catch((error) => {
                    this.isLoadingTuk = false;
                });
        },
        getRupiah(amount) {
            return util.getRupiah(amount);
        },
    },
    components: { PageTitle, Loader },
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
        <div class="col-md-4">
            <div class="card card-pricing position-relative">
                <Loader v-if="isLoadingWelder" />
                <div class="card-body text-center">
                    <p class="card-pricing-plan-name fw-bold text-uppercase">
                        {{ costWelderMember.typePrice }}
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
                        <li
                            v-for="(
                                benefit, index
                            ) in costWelderMember.benefits"
                            :key="index"
                            v-html="benefit.description"
                        ></li>
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

        <div class="col-md-4">
            <div class="card card-pricing position-relative">
                <Loader v-if="isLoadingCompany" />
                <div class="card-body text-center">
                    <p class="card-pricing-plan-name fw-bold text-uppercase">
                        {{ costCompanyMember.typePrice }}
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
                        <li
                            v-for="(
                                benefit, index
                            ) in costCompanyMember.benefits"
                            :key="index"
                            v-html="benefit.description"
                        ></li>
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

        <div class="col-md-4">
            <div class="card card-pricing position-relative">
                <Loader v-if="isLoadingTuk" />
                <div class="card-body text-center">
                    <p class="card-pricing-plan-name fw-bold text-uppercase">
                        {{ costTuk.typePrice }}
                    </p>
                    <h2 class="card-pricing-price">
                        {{ getRupiah(costTuk.nominalPrice) }}
                        <span>/ Tahun</span>
                    </h2>
                    <ul class="card-pricing-features">
                        <li class="h5 my-0 py-0 fw-bold text-uppercase">
                            Keuntungan
                        </li>
                        <hr />
                        <li
                            v-for="(benefit, index) in costTuk.benefits"
                            :key="index"
                            v-html="benefit.description"
                        ></li>
                    </ul>
                    <router-link
                        :to="{ name: 'Member Operator' }"
                        class="btn btn-primary mt-4 mb-2 rounded-pill"
                    >
                        Pilih Member
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
