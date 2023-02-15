<script>
import Success from "../../../components/notifications/Success.vue";

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
                    <label for="skillName">Nama Keahlian</label>
                    <input
                        type="text"
                        class="form-control"
                        id="skillName"
                        value="Keahlian 1"
                    />
                </div>
                <div class="mb-2">
                    <label for="skillDescription">Deskripsi</label>
                    <textarea class="form-control" rows="5">
Deskripsi Keahlian 1</textarea
                    >
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

    <Success :msg="'data berhasil diperbaharui.'" />
</template>
