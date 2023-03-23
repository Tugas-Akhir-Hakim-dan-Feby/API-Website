<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";

export default {
    data() {
        return {
            form:{
                name: "",
                email: "",
                position: "",
                phone: "",
            },
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
                    <label
                    for="name"
                    >Nama Lengkap</label>
                    <input 
                    type="text" 
                    class="form-control" 
                    id="name"
                    v-model="form.name"
                    />
                </div>
                <div class="mb-2">
                    <label
                    for="email"
                    >Email</label>
                    <input 
                    type="text" 
                    class="form-control" 
                    id="email"
                    v-model="form.email"
                    />
                </div>
                <div class="mb-2">
                    <label
                    for="position"
                    >Jabatan</label>
                    <input 
                    type="text" 
                    class="form-control" 
                    id="position"
                    v-model="form.position"
                    />
                </div>
                <div class="mb-2">
                    <label
                    for="phone"
                    >Telepon</label>
                    <input 
                    type="text" 
                    class="form-control"
                    id="phone"
                    v-model="form.phone"
                    />
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
