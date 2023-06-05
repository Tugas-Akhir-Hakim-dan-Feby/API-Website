<script>
import Success from "../../../components/notifications/Success.vue";
export default {
    data() {
        return {
            form: {},
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        setForm(user) {
            this.form = {
                name: user.name,
                email: user.email,
            };
        },
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.setForm(response.user);
                })
                .catch((err) => {});
        },
        handleSubmit() {
            $("#successModal").modal("show");
        },
    },
    components: { Success },
};
</script>
<template>
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>Informasi Profil</h4>
            <p>Perbarui informasi profil akun Anda.</p>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3">Nama</label>
                        <div class="col">
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.name"
                            />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3">Email</label>
                        <div class="col">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3">Telepon</label>
                        <div class="col">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3">Tempat Lahir</label>
                        <div class="col">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3">Tanggal Lahir</label>
                        <div class="col">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3">Jenis Kelamin</label>
                        <div class="col">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3">Jabatan</label>
                        <div class="col">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3">Alamat</label>
                        <div class="col">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-sm btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <Success :url="{ name: 'My Profile' }" :msg="'data berhasil disimpan.'" />
</template>
