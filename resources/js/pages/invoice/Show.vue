<script>
import PageTitle from "../../components/PageTitle.vue";
import PusherUtil from "../../store/utils/pusher";

export default {
    props: ["externalId", "costId"],
    data() {
        return {
            user: {},
            payment: {},
        };
    },
    created() {},
    mounted() {
        this.getUser();
        this.getPayment();

        setInterval(() => {
            PusherUtil.privateMessage(
                "App.Models.Payment." + this.externalId,
                "MessagePayment",
                (response) => {
                    if (response.message.status == this.$store.state.PAID) {
                        this.$router.push({ name: "Invoice Success" });
                    }
                }
            );
        }, 1000);
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
            this.$store
                .dispatch("showData", ["payment", this.externalId])
                .then((response) => {
                    this.payment = response.data;
                })
                .catch((err) => {});
        },
        formatRupiah(number) {
            const formatter = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            });
            return formatter.format(number ?? 0);
        },
        redirect() {
            this.$router.push({ name: "Invoice Success" });
        },
    },
    components: { PageTitle },
};
</script>
<template>
    <PageTitle :title="'Invoice #' + externalId" />

    <div class="row">
        <div class="col-12">
            <div class="card">
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
                                class="m-0 btn btn-sm text-white"
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
                                                <b class="text-capitalize">{{
                                                    payment.description
                                                }}</b>
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
                                :href="`/print/invoice/${externalId}`"
                                target="_blank"
                                class="btn-sm btn btn-info"
                                v-if="payment.status == $store.state.PAID"
                                >Cetak</a
                            >
                            <a
                                :href="payment.paymentLink"
                                target="_blank"
                                class="btn-sm btn btn-primary"
                                v-else
                                >Bayar</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
