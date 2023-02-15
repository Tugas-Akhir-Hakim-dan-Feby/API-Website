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
        <label class="col-3 col-form-label">Nama Lengkap</label>
        <div class="col-9">
            <input type="text" class="form-control" value="Dominic Keller" />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Jenis Kelamin</label>
        <div class="col-9">
            <select name="" id="" class="form-select">
                <option value=""></option>
                <option value="" selected>Laki-laki</option>
                <option value="">Perempuan</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Tempat Lahir</label>
        <div class="col-9">
            <input type="text" class="form-control" value="Indramayu" />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Tanggal Lahir</label>
        <div class="col-9">
            <input type="date" class="form-control" value="2001-10-24" />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Jabatan</label>
        <div class="col-9">
            <input type="text" class="form-control" value="Administrator" />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Telepon</label>
        <div class="col-9">
            <input type="text" class="form-control" value="081234567890" />
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-3 col-form-label">Alamat</label>
        <div class="col-9">
            <textarea name="" id="" class="form-control">
Puntang, Kec. Losarang, Kabupaten Indramayu, Jawa Barat</textarea
            >
        </div>
    </div>
    <div>
        <button class="btn btn-success" @click="handleSubmit">Simpan</button>
    </div>

    <Success :url="{ name: 'My Profile' }" :msg="'data berhasil disimpan.'" />
</template>
