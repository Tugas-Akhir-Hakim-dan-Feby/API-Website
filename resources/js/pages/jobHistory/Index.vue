<script>
import Loader from "../../components/Loader.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import PaginationUtil from "../../store/utils/pagination";
import Util from "../../store/utils/util";

export default {
    data() {
        return {
            isLoading: false,
            filters: {
                status: 0,
            },
            histories: [],
            metaPagination: {},
            errors: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
        };
    },
    mounted() {
        this.getHistories();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getHistories() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `status=${this.filters.status}`,
            ].join("&");

            this.$store
                .dispatch("getData", ["history-job", params])
                .then((response) => {
                    this.isLoading = false;
                    this.histories = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getRupiah(amount) {
            return Util.getRupiah(amount);
        },
        onUpdateStatus() {
            this.getHistories();
        },
    },
    components: { PageTitle, Loader, Pagination },
};
</script>

<template>
    <PageTitle title="Riwayat Lamaran">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Riwayat Lamaran</li>
        </ol>
    </PageTitle>

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div class="d-md-flex justify-content-between align-items-center">
                <div class="me-md-2 me-0">
                    <select
                        class="form-select"
                        @change="onUpdateStatus"
                        v-model="filters.status"
                    >
                        <option value="0">Semua Lamaran</option>
                        <option value="1">Diterima</option>
                        <option value="2">Ditolak</option>
                    </select>
                </div>

                <Pagination
                    :pagination="metaPagination"
                    @onPageChange="onPageChange($event)"
                />
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Perusahaan</th>
                            <th>Jenis Lowongan</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Penempatan</th>
                            <th>Perkiraan Gaji</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="histories.length < 1">
                            <td class="text-center" colspan="7">
                                data riwayat lamaran tidak tersedia.
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(history, index) in histories"
                            :key="index"
                        >
                            <th v-html="iteration(index)"></th>
                            <td v-html="history.companyName"></td>
                            <td v-html="history.jobTitle"></td>
                            <td v-html="history.workType"></td>
                            <td v-html="history.placement"></td>
                            <td v-html="getRupiah(history.salary)"></td>
                            <td>
                                <span
                                    class="badge"
                                    :class="
                                        history.status.code == 1
                                            ? 'bg-success'
                                            : history.status.code == 2
                                            ? 'bg-danger'
                                            : 'bg-primary'
                                    "
                                    >{{ history.status.message }}</span
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
