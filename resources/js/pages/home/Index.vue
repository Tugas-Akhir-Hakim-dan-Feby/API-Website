<script>
import PageTitle from "../../components/PageTitle.vue";

export default {
    components: { PageTitle },
};
</script>

<template>
    <PageTitle :title="'Dashboard'">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </PageTitle>

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
                                            <span>71</span>
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
                                            <span>31</span>
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
                                            <span>90</span>
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
        </div>
    </div>
</template>
