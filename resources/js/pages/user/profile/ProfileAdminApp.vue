<script>
import Success from "../../../components/notifications/Success.vue";
export default {
    data() {
        return {
            form: {},
            errors: {},
            isLoading: false,
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        setForm(user) {
            this.form = {
                id: user.uuid,
                name: user.name,
                email: user.email,
            };
        },
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.setForm(response.user);
                })
                .catch((err) => {});
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", [
                    "user/admin-app",
                    this.form.id,
                    this.form,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.showAlert = true;
                    this.getUser();
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
    },
    components: { Success },
};
</script>
<template>
    <div class="row mt-3">
        <div class="col-lg-4">
            <h4>Informasi Pribadi</h4>
            <p>Perbarui informasi profil akun Anda.</p>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <form @submit.prevent="handleSubmit" method="post">
                    <div class="card-body">
                        <div
                            class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert"
                            v-if="typeof errors == 'string'"
                        >
                            <button
                                type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>
                            <strong>Galat - </strong> {{ errors }}
                        </div>

                        <div
                            class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                            role="alert"
                            v-if="showAlert"
                        >
                            <button
                                type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>
                            <strong>berhasil - </strong> informasi akun berhasil
                            diperbaharui
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3">Nama</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.name,
                                    }"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.name"
                                    v-for="(error, index) in errors.name"
                                    :key="index"
                                    v-html="error"
                                ></div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Email</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.email"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.email,
                                    }"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.email"
                                    v-for="(error, index) in errors.email"
                                    :key="index"
                                    v-html="error"
                                ></div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button class="btn btn-sm btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <Success :url="{ name: 'My Profile' }" :msg="'data berhasil disimpan.'" />
</template>
