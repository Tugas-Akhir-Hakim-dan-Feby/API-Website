<script>
import PageTitle from "../../components/PageTitle.vue";
import Loader from "../../components/Loader.vue";
import Pagination from "../../components/Pagination.vue";
import util from "../../store/utils/util";

import Success from "../../components/notifications/Success.vue";
export default {
    data() {
        return {
            costs: [],
            cost: {},
            errors: {},
            form: {
                nominalPrice: "",
            },
            isLoading: false,
        };
    },
    mounted() {
        this.getCosts();
    },
    methods: {
        getCosts() {
            this.isLoading = true;
            this.$store
                .dispatch("getData", ["cost", ""])
                .then((response) => {
                    this.isLoading = false;
                    this.costs = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getRupiah(amount) {
            return util.getRupiah(amount);
        },
        setCost(cost) {
            this.cost = cost;
        },
        handleSubmit() {
            this.errors = {};
            this.isLoading = true;
            this.$store
                .dispatch("updateData", ["cost", this.cost.uuid, this.form])
                .then((response) => {
                    this.isLoading = false;
                    this.getCosts();
                    this.form = {};
                    $("#editCost").modal("hide");
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
    },
    components: { PageTitle, Pagination, Loader, Success },
};
</script>
<template>
    <PageTitle :title="'Edit Harga'">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Payment Cost' }"
                    >Data Harga</router-link
                >
            </li>
            <li class="breadcrumb-item active">Edit Harga</li>
        </ol>
    </PageTitle>

    <div class="row">
        <div class="col-lg-8 col-md-8 offset-lg-2 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label>Jenis Harga</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Nominal Harga</label>
                        <input type="text" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Success msg="data harga berhasil diperbaharui." />
</template>
