<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";

export default {
    data() {
        return {
            msg: "",
        };
    },
    methods: {
        handleSubmit() {
            $("#successModal").modal("show");
            this.msg = "data berhasil ditambahkan.";
        },
    },
    components: { PageTitle, Success },
};
</script>
<template>
    <PageTitle :title="'Tambah Pengguna API Pusat'" />

    <div class="card">
        <form @submit.prevent="handleSubmit">
            <div class="card-body">
                <div class="mb-2">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" />
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="text" class="form-control" />
                </div>
                <div class="mb-2">
                    <label>Jabatan</label>
                    <input type="text" class="form-control" />
                </div>
                <div class="mb-2">
                    <label>Telepon</label>
                    <input type="text" class="form-control" />
                </div>
            </div>
            <div class="card-footer border-top d-flex justify-content-between">
                <router-link
                    :to="{ name: 'User Hub' }"
                    class="btn btn-secondary"
                    >Batal</router-link
                >
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <Success :url="{ name: 'User Hub' }" :msg="msg" />
</template>
