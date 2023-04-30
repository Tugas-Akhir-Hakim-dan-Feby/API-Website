<script>
import PageTitle from "../../components/PageTitle.vue";
export default {
    components: { PageTitle },
};
</script>
<template>
    <PageTitle title="Daftar Member" />

    <div class="row mb-3">
        <div class="col-md-4 offset-lg-2">
            <div class="card card-pricing">
                <div class="card-body text-center">
                    <p class="card-pricing-plan-name fw-bold text-uppercase">
                        Welder Member
                    </p>
                    <h2 class="card-pricing-price">
                        Rp 100.000 <span>/ Tahun</span>
                    </h2>
                    <ul class="card-pricing-features">
                        <li>10 GB Storage</li>
                        <li>500 GB Bandwidth</li>
                        <li>No Domain</li>
                        <li>1 User</li>
                        <li>Email Support</li>
                        <li>24x7 Support</li>
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
            <div class="card card-pricing">
                <div class="card-body text-center">
                    <p class="card-pricing-plan-name fw-bold text-uppercase">
                        Perusahaan Member
                    </p>
                    <h2 class="card-pricing-price">
                        Rp 100.000 <span>/ Tahun</span>
                    </h2>
                    <ul class="card-pricing-features">
                        <li>100 GB Storege</li>
                        <li>Unlimited Bandwidth</li>
                        <li>10 Domain</li>
                        <li>Unlimited User</li>
                        <li>Email Support</li>
                        <li>24x7 Support</li>
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
