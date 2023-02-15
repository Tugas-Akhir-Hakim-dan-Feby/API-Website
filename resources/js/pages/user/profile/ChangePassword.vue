<script>
import Success from "../../../components/notifications/Success.vue";
export default {
    data() {
        return {};
    },
    methods: {
        handleSubmit() {
            $("#successModal").modal("show");
        },
    },
    components: { Success },
};
</script>
<template>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Email</label>
        <div class="col-9">
            <input
                type="email"
                class="form-control"
                value="dominic.keller@mailinator.com"
                disabled
            />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Password Saat Ini</label>
        <div class="col-9">
            <input type="password" class="form-control" />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Password Baru</label>
        <div class="col-9">
            <input type="password" class="form-control" />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Konfirmasi Password Baru</label>
        <div class="col-9">
            <input type="password" class="form-control" />
        </div>
    </div>
    <div>
        <button class="btn btn-success" @click="handleSubmit">Simpan</button>
    </div>
    <Success :url="{ name: 'My Profile' }" :msg="'data berhasil disimpan.'" />
</template>
