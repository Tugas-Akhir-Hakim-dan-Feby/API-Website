<script>
import Success from "../../components/notifications/Success.vue";

export default {
    props: ["id"],
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
                    <input
                        type="text"
                        class="form-control"
                        id="branchName"
                        value="Cabang 1"
                    />
                </div>
                <div class="mb-2">
                    <label for="branchAddress">Alamat Cabang</label>
                    <input
                        type="text"
                        class="form-control"
                        id="branchAddress"
                        value="Jl. Raya No. 1"
                    />
                </div>
                <div class="mb-2">
                    <label for="branchPhone">No. Telepon Cabang</label>
                    <input
                        type="text"
                        class="form-control"
                        id="branchPhone"
                        value="081234567890"
                    />
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="onCancel"
                >
                    Batal
                </button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </form>

    <Success :msg="'data berhasil diperbaharui.'" />
</template>
