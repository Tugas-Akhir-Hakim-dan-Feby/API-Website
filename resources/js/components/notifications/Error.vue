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
                    <h5 class="modal-title" id="errorModalLabel">galat</h5>
                </div>
                <div class="modal-body" v-html="msg"></div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal"
                        v-if="!url"
                    >
                        OK
                    </button>
                    <button
                        @click="handleOk"
                        type="button"
                        class="btn btn-primary"
                        v-else
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
