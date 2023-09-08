<script>
import Success from "../../../components/notifications/Success.vue";
import iziToast from "izitoast";

export default {
    data() {
        return {
            form: {},
            errors: {},
            isLoading: false,
        };
    },
    computed: {
        maxDateBirth() {
            let date = new Date();
            date.setFullYear(date.getFullYear() - 17);
            date.setMonth(date.getMonth());
            date.setDate(date.getDate());

            let formattedDate = date.toISOString().split("T")[0];
            return formattedDate;
        },
    },
    mounted() {
        this.getUser();
    },
    methods: {
        setForm(user) {
            this.form = {
                name: user.name,
                email: user.email,
                phone: user.adminHub?.phone,
                birthPlace: user.adminHub?.birthPlace,
                dateBirth: user.adminHub?.dateBirth,
                gender: user.adminHub?.gender,
                membershipCard: user.membershipCard,
                position: user.adminHub?.position,
                address: user.adminHub?.address,
                id: user.uuid,
            };
        },
        getUser() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.isLoading = false;
                    this.setForm(response.user);
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["user/hub", this.form.id, this.form])
                .then((response) => {
                    this.isLoading = false;
                    this.getUser();
                    iziToast.success({
                        title: "Selamat",
                        message: "data anda berhasil diperbaharui",
                        position: "topCenter",
                    });
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
            <h4>Informasi Profil</h4>
            <p>Perbaharui informasi profil akun Anda.</p>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <form method="post" @submit.prevent="handleSubmit">
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

                        <div class="row mb-3">
                            <label class="col-sm-3">No. KTA</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.membershipCard"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.membershipCard,
                                    }"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.membershipCard"
                                    v-for="(
                                        error, index
                                    ) in errors.membershipCard"
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
                                    type="email"
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
                        <div class="row mb-3">
                            <label class="col-sm-3">Telepon</label>
                            <div class="col">
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model="form.phone"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.phone,
                                    }"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.phone"
                                    v-for="(error, index) in errors.phone"
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
                            <label class="col-sm-3">Tempat Lahir</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.birthPlace"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.birthPlace,
                                    }"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.birthPlace"
                                    v-for="(error, index) in errors.birthPlace"
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
                            <label class="col-sm-3">Tanggal Lahir</label>
                            <div class="col">
                                <input
                                    type="date"
                                    class="form-control"
                                    v-model="form.dateBirth"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.dateBirth,
                                    }"
                                    :max="maxDateBirth"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.dateBirth"
                                    v-for="(error, index) in errors.dateBirth"
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
                            <label class="col-sm-3">Jenis Kelamin</label>
                            <div class="col">
                                <select
                                    v-model="form.gender"
                                    class="form-select"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.gender,
                                    }"
                                >
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>

                                <div
                                    class="invalid-feedback"
                                    v-if="errors.gender"
                                    v-for="(error, index) in errors.gender"
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
                            <label class="col-sm-3">Jabatan</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.position"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.position,
                                    }"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.position"
                                    v-for="(error, index) in errors.position"
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
                            <label class="col-sm-3">Alamat</label>
                            <div class="col">
                                <textarea
                                    v-model="form.address"
                                    class="form-control"
                                    rows="5"
                                    :disabled="isLoading"
                                    :class="{
                                        'is-invalid': errors.address,
                                    }"
                                ></textarea>

                                <div
                                    class="invalid-feedback"
                                    v-if="errors.address"
                                    v-for="(error, index) in errors.address"
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
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-primary btn-sm"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <Success :url="{ name: 'My Profile' }" :msg="'data berhasil disimpan.'" />
</template>
