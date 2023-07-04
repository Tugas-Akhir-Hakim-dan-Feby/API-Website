<script>
import PageTitle from "../../components/PageTitle.vue";
import Loader from "../../components/Loader.vue";
import Pagination from "../../components/Pagination.vue";
import PaginationUtil from "../../store/utils/pagination";
import util from "../../store/utils/util";

export default {
    data() {
        return {
            invoices: [],
            metaPagination: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
                status: "",
                sortDirection: "desc",
            },
            isLoading: false,
        };
    },
    mounted() {
        this.getInovices();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getInovices() {
            this.isLoading = true;

            const params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
                `status=${this.filters.status}`,
                `sort_direction=${this.filters.sortDirection}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["payment", params])
                .then((response) => {
                    this.isLoading = false;
                    this.invoices = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        getRupiah(amount) {
            return util.getRupiah(amount);
        },
        onSearch() {
            setTimeout(() => {
                this.getInovices();
            }, 1000);
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getInovices();
        },
        onStatusChange() {
            this.getInovices();
        },
    },
    components: { PageTitle, Pagination, Loader },
};
</script>
<template>
    <PageTitle title="Data Rekapitulasi Faktur">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Data Rekapitulasi Faktur</li>
        </ol>
    </PageTitle>

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-end align-items-center mb-2"
            >
                <div
                    class="d-md-flex justify-content-between align-items-center"
                >
                    <div class="me-md-2 me-0 mb-md-0 mb-2">
                        <select
                            class="form-select"
                            @change="onStatusChange()"
                            v-model="filters.status"
                        >
                            <option value="">pilih status</option>
                            <option value="PAID">PAID</option>
                            <option value="PENDING">PENDING</option>
                        </select>
                    </div>

                    <div class="me-md-2 me-0">
                        <input
                            type="search"
                            class="form-control"
                            placeholder="pencarian"
                            @input="onSearch"
                            v-model="filters.search"
                        />
                    </div>

                    <Pagination
                        :pagination="metaPagination"
                        @onPageChange="onPageChange($event)"
                    />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pengguna</th>
                            <th>Email Pengguna</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="invoices.length < 1">
                            <td colspan="5" class="text-center">
                                data rekapitulasi faktur tidak ada
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(invoice, index) in invoices"
                            :key="index"
                        >
                            <th v-html="iteration(index)"></th>
                            <td>
                                {{ invoice.user?.name }}
                            </td>
                            <td>
                                {{ invoice.user?.email }}
                            </td>
                            <td>
                                {{ invoice.description }}
                                <br />
                                <small class="text-muted"
                                    >Kode: {{ invoice.externalId }}</small
                                >
                                <br />
                                <small class="text-muted"
                                    >Total:
                                    {{ getRupiah(invoice.amount) }}</small
                                >
                                <br />
                                <small class="text-muted"
                                    >Status:
                                    <span
                                        class="badge"
                                        :class="
                                            invoice.status == 'PAID'
                                                ? 'bg-success'
                                                : 'bg-warning'
                                        "
                                        style="font-size: 0.8em"
                                        >{{ invoice.status }}</span
                                    ></small
                                >
                            </td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Show Payment Recapitulation',
                                        params: {
                                            externalId: invoice.externalId,
                                        },
                                    }"
                                    class="btn btn-info text-white btn-sm me-2"
                                >
                                    Detail
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
