<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
        isBack: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        onBack() {
            this.$router.back(-1);
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

                <h4 class="page-title">
                    <i
                        class="font-20 uil-arrow-left"
                        style="cursor: pointer"
                        v-if="isBack"
                        @click="onBack"
                    ></i>
                    {{ title }}
                </h4>
            </div>
        </div>
    </div>
</template>
