<script>
import FileSearching from "../../components/icons/FileSearching.vue";

export default {
    methods: {},
    components: { FileSearching },
};
</script>
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="text-center">
                    <h1 class="text-error mt-5">404</h1>
                    <h4 class="text-uppercase text-danger mt-3">
                        Page Not Found
                    </h4>

                    <button @click="$router.back()" class="btn btn-info mt-3">
                        <i class="mdi mdi-reply"></i> Kembali
                    </button>
                </div>
                <!-- end /.text-center-->
            </div>
            <!-- end col-->
        </div>
    </div>
</template>
