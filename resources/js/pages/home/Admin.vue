<script>
export default {
    data() {
        return {
            skillChart: {},
        };
    },
    mounted() {
        this.getSkillChart();
    },
    methods: {
        getSkillChart() {
            this.$store
                .dispatch("showChart", ["chart/skill", {}])
                .then((response) => {
                    this.skillChart = response;
                })
                .catch((err) => {});
        },
    },
};
</script>
<template>
    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Branch' }"
                                ><div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <i
                                            class="ri-list-check-2 text-muted font-24"
                                        ></i>
                                        <h3 class="text-dark">
                                            <span>10</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Pengguna API Cabang
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Expert' }">
                                <div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <i
                                            class="ri-group-line text-muted font-24"
                                        ></i>
                                        <h3 class="text-dark">
                                            <span>12</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Pengguna Pakar
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Company' }">
                                <div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <h3 class="text-dark">
                                            <span>9</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Perusahaan Member
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <router-link :to="{ name: 'User Member' }">
                                <div
                                    class="card rounded-0 shadow-none m-0 border-start border-light"
                                >
                                    <div class="card-body text-center">
                                        <h3 class="text-dark">
                                            <span>20</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">
                                            Jumlah Welder Member
                                        </p>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Perkembangan Minat Welder</h3>
                    <span class="small">({{ skillChart.info }})</span>
                </div>
                <div class="card-body">
                    <line-chart
                        :data="skillChart.data"
                        xtitle="Jenis Keahlian"
                        ytitle="Total"
                        loading="Loading..."
                        :download="true"
                    ></line-chart>
                </div>
            </div>
        </div>
    </div>
</template>
