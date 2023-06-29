<script>
import PageTitle from "../../components/PageTitle.vue";
import Loader from "../../components/Loader.vue";
import PusherUtil from "../../store/utils/pusher";

export default {
    props: ["externalId"],
    data() {
        return {
            user: {},
            payment: {},
            isLoading: false,
        };
    },
    created() {},
    mounted() {
        this.getUser();
        this.getPayment();
    },
    methods: {
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.user = response.user;
                })
                .catch((err) => {});
        },
        getPayment() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["payment", this.externalId])
                .then((response) => {
                    this.isLoading = false;
                    this.payment = response.data;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        onBack(e) {
            this.$router.push({ name: "Payment History" });
        },
        formatRupiah(number) {
            const formatter = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            });
            return formatter.format(number ?? 0);
        },
    },
    components: { PageTitle, Loader },
};
</script>
<template>
    <PageTitle
        :title="'Invoice #' + externalId"
        :isBack="true"
        @onBack="onBack($event)"
    >
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Payment History' }"
                    >Riwayat Pembayaran</router-link
                >
            </li>
            <li class="breadcrumb-item active">Detail Pembayaran</li>
        </ol>
    </PageTitle>

    <div class="row">
        <div class="col-12">
            <div class="card position-relative">
                <Loader v-if="isLoading" />
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-start mb-3">
                            <img
                                src="../../../images/api-iws.png"
                                alt="dark logo"
                                height="75"
                            />
                        </div>
                        <div class="float-end">
                            <h4
                                class="m-0 btn text-white btn-sm"
                                style="cursor: default"
                                :class="
                                    payment.status == $store.state.PAID
                                        ? 'btn-success'
                                        : 'btn-warning'
                                "
                            >
                                {{ payment.status }}
                            </h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h6>Membayar ke:</h6>
                            <address>API-IWS</address>
                        </div>

                        <div class="col-sm-4">
                            <h6>Ditaggih ke:</h6>
                            <address>{{ user.name }}</address>
                        </div>

                        <div class="col-sm-4">
                            <div class="text-sm-end"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mt-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Deskripsi</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <b>{{ payment.description }}</b>
                                                <br />
                                            </td>
                                            <td class="text-end">
                                                {{
                                                    formatRupiah(payment.amount)
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <div class="float-end mt-3 mt-sm-0">
                                <h3>{{ formatRupiah(payment.amount) }}</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="d-print-none mt-4">
                        <div class="text-end">
                            <a
                                href=""
                                target="_blank"
                                class="btn btn-info btn-sm"
                                >Cetak</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
