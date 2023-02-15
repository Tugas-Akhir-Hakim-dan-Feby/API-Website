<script>
export default {
    methods: {
        handleSubmit() {
            this.$emit("onSuccess", true);
        },
    },
};
</script>
<template>
    <div class="mb-2">
        <label for="fullname" class="form-label">Nama Lengkap</label>
        <input class="form-control" type="text" id="fullname" />
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Email</label>
        <input class="form-control" type="email" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Telepon</label>
        <input class="form-control" type="text" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Nama Perusahaan</label>
        <input class="form-control" type="text" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label"
            >Jabatan Dalam Perusahaan</label
        >
        <input class="form-control" type="text" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Alamat Perusahaan</label>
        <textarea name="" id="" class="form-control"></textarea>
    </div>

    <div class="mb-2 text-center">
        <button class="btn btn-primary form-control" @click="handleSubmit">
            Register
        </button>
    </div>
</template>
