<script>
export default {
    props: {
        message: {
            type: String,
            default: "",
        },
    },
};
</script>

<template>
    <div
        class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
        role="alert"
    >
        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
        <strong>Error - </strong> terjadi kesalahan <br />
        <strong>Message - </strong>
        {{ message }}
    </div>
</template>
