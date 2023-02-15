<script>
import PageTitle from "../../components/PageTitle.vue";
import ChangePassword from "./profile/ChangePassword.vue";
import MyBio from "./profile/MyBio.vue";

export default { components: { PageTitle, MyBio, ChangePassword } };
</script>
<template>
    <PageTitle :title="'Profil Saya'" />

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-3">
                        <img
                            src="https://ui-avatars.com/api/?background=random&size=100&rounded=true&length=2&name=Dominic Keller"
                            alt=""
                        />
                    </div>
                    <h3 class="text-center">Dominic Keller</h3>
                    <p class="text-muted text-center">Admin API Pusat</p>
                    <hr />
                    <p class="fw-semibold text-dark">Tentang Saya</p>
                    <p class="text-muted">Nama Lengkap : Dominic Keller</p>
                    <p class="text-muted">Jenis Kelamin : Laki-laki</p>
                    <p class="text-muted">Tempat Lahir : Indramayu</p>
                    <p class="text-muted">Tanggal Lahir : 24 Oktober 2001</p>
                    <p class="text-muted">
                        Email : dominic.keller@mailinator.com
                    </p>
                    <p class="text-muted">Jabatan : Administrator</p>
                    <p class="text-muted">Telepon : 081234567890</p>
                    <p class="text-muted">
                        Alamat : Puntang, Kec. Losarang, Kabupaten Indramayu,
                        Jawa Barat
                    </p>
                </div>
            </div>
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
                        >
                            <span class="d-none d-md-block">Biodata Saya</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            href="#changePassword"
                            data-bs-toggle="tab"
                            aria-expanded="true"
                            class="nav-link"
                        >
                            <i
                                class="mdi mdi-account-circle d-md-none d-block"
                            ></i>
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
