<script>
export default {
    props: ["msg", "url"],
    methods: {
        handleOk() {
            $("#errorModal").modal("hide");
            this.$router.push(this.url);
        },
    },
};
</script>
<template>
    <div
        class="modal fade"
        id="errorModal"
        tabindex="-1"
        aria-labelledby="errorModalLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                </div>
                <div class="modal-body" v-html="msg"></div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal"
                        v-if="!url"
                    >
                        Tutup
                    </button>
                    <button
                        @click="handleOk"
                        type="button"
                        class="btn btn-sm btn-secondary"
                        v-else
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
