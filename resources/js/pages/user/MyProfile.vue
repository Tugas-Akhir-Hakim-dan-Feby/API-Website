<script>
import PageTitle from "../../components/PageTitle.vue";
import ChangePassword from "./profile/ChangePassword.vue";
import MyBio from "./profile/MyBio.vue";
import DetailProfile from "./profile/DetailProfile.vue";

export default {
    components: { PageTitle, MyBio, ChangePassword, DetailProfile },
};
</script>
<template>
    <PageTitle :title="'Profil Saya'" />

    <div class="row">
        <div class="col-lg-4">
            <DetailProfile />
        </div>
        <div class="col-lg-8">
            <div class="card">
                <ul class="nav nav-tabs nav-bordered mb-3">
                    <li class="nav-item">
                        <a
                            href="#myBio"
                            data-bs-toggle="tab"
                            aria-expanded="false"
                            class="nav-link active"
                            title="Biodata Saya"
                        >
                            <i
                                class="mdi mdi-account-circle d-md-none d-block"
                            ></i>
                            <span class="d-none d-md-block">Biodata Saya</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            href="#changePassword"
                            data-bs-toggle="tab"
                            aria-expanded="true"
                            class="nav-link"
                            title="Ubah Password"
                        >
                            <i class="mdi mdi-key d-md-none d-block"></i>
                            <span class="d-none d-md-block">Ubah Password</span>
                        </a>
                    </li>
                </ul>
                <div class="card-body pt-0">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="myBio">
                            <MyBio />
                        </div>
                        <div class="tab-pane" id="changePassword">
                            <ChangePassword />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
