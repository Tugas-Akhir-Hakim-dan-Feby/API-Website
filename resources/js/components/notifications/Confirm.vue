<script>
export default {
    props: {
        msg: {
            default: null,
            type: String,
        },
    },
    methods: {
        onDelete() {
            this.$emit("onDelete", 1);
        },
    },
};
</script>
<template>
    <div
        class="modal fade"
        id="confirmModal"
        tabindex="-1"
        aria-labelledby="confirmModalLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">
                        konfirmasi
                    </h5>
                </div>
                <div
                    class="modal-body"
                    v-html="msg ?? 'apakah anda yakin data akan dihapus?'"
                ></div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button
                        @click="onDelete"
                        type="button"
                        class="btn btn-danger"
                    >
                        <slot> Hapus </slot>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
