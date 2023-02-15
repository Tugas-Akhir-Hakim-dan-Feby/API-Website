<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
    },
};
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <slot></slot>
                </div>
                <h4 class="page-title" v-html="title"></h4>
            </div>
        </div>
    </div>
</template>
