<script>
import Success from "../../components/notifications/Success.vue";

export default {
    data() {
        return {};
    },
    methods: {
        onCancel() {
            this.$emit("onCancel", true);
        },
        handleSubmit() {
            $("#successModal").modal("show");
            this.$emit("onCancel", true);
        },
    },
    components: { Success },
};
</script>
<template>
    <form @submit.prevent="handleSubmit" method="post">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <label for="branchName">Nama Cabang</label>
                    <input type="text" class="form-control" id="branchName" />
                </div>
                <div class="mb-2">
                    <label for="branchAddress">Alamat Cabang</label>
                    <input
                        type="text"
                        class="form-control"
                        id="branchAddress"
                    />
                </div>
                <div class="mb-2">
                    <label for="branchPhone">No. Telepon Cabang</label>
                    <input type="text" class="form-control" id="branchPhone" />
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button class="btn btn-secondary" @click="onCancel">
                    Batal
                </button>
                <button class="btn btn-success">Simpan</button>
            </div>
        </div>
    </form>

    <Success :msg="'data berhasil ditambahkan.'" />
</template>
