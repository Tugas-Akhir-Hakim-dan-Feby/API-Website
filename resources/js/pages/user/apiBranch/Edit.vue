<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";

export default {
    data() {
        return {
            msg: "",
            branches: [],
        };
    },
    methods: {
        handleSubmit() {
            $("#successModal").modal("show");
            this.msg = "data berhasil disimpan.";
        },
    },
    components: { PageTitle, Success },
};
</script>
<template>
    <PageTitle :title="'Edit Pengguna API Pusat'" />

    <div class="card">
        <form @submit.prevent="handleSubmit">
            <div class="card-body">
                <div class="mb-2">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" value="John Doe" />
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input
                        type="text"
                        class="form-control"
                        value="john.doe@mailinator.com"
                    />
                </div>
                <div class="mb-2">
                    <label>Cabang</label>
                    <select name="" id="" class="form-control">
                        <option value="">Pilih Cabang</option>
                        <option
                            v-for="branch in branches"
                            :value="branch.id"
                            :selected="branch.id == 1"
                        >
                            {{ branch.branchName }}
                        </option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Jabatan</label>
                    <input
                        type="text"
                        class="form-control"
                        value="Sekretaris"
                    />
                </div>
                <div class="mb-2">
                    <label>Telepon</label>
                    <input
                        type="text"
                        class="form-control"
                        value="081234567890"
                    />
                </div>
            </div>
            <div class="card-footer border-top d-flex justify-content-between">
                <router-link
                    :to="{ name: 'User Branch' }"
                    class="btn btn-secondary"
                    >Batal</router-link
                >
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <Success :url="{ name: 'User Branch' }" :msg="msg" />
</template>
