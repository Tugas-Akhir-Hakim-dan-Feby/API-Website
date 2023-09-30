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
    },
    components: { PageTitle, Pagination, Loader, Success },
};
</script>
<template>
    <PageTitle :title="'Data Harga'">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Data Harga</li>
        </ol>
    </PageTitle>

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Jenis Harga</th>
                            <th>Nominal Harga</th>
                            <th>Benefit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="costs.length < 1">
                            <td colspan="5" class="text-center">
                                data harga tidak ada
                            </td>
                        </tr>
                        <tr v-else v-for="(cost, index) in costs" :key="index">
                            <th v-html="index + 1"></th>
                            <td v-html="cost.typePrice"></td>
                            <td v-html="getRupiah(cost.nominalPrice)"></td>
                            <td>
                                <ul v-if="cost.benefits.length > 0">
                                    <li
                                        v-for="(
                                            benefit, index
                                        ) in cost.benefits"
                                        :key="index"
                                        v-html="benefit.description"
                                    ></li>
                                </ul>
                                <span v-else>-</span>
                            </td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Payment Cost Edit',
                                        params: { id: cost.id },
                                    }"
                                    class="btn btn-warning text-white btn-sm me-2 mb-2"
                                >
                                    Edit
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Success msg="data harga berhasil diperbaharui." />
</template>
