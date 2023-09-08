<script>
import Success from "../../../components/notifications/Success.vue";
import iziToast from "izitoast";
import dayjs from "dayjs";
import "dayjs/locale/id";
import UploadFileCompanyMember from "./uploadFile/UploadFileCompanyMember.vue";

export default {
    data() {
        return {
            form: {},
            errors: {},
            document: {},
            isLoading: false,
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        setForm(user) {
            this.form = {
                name: user.name,
                email: user.email,
                membershipCard: user.membershipCard,
                companyName: user.companyMember?.companyName,
                companyDirector: user.companyMember?.companyDirector,
                companyAddress: user.companyMember?.companyAddress,
                companyProfile: user.companyMember?.companyProfile,
                companyEmail: user.companyMember?.companyEmail,
                phone: user.companyMember?.phone,
                facsmile: user.companyMember?.facsmile,
                id: user.uuid,
            };

            this.document = {
                id: user.uuid,
                documentLegality: user.companyMember?.companyLegality,
                companyLogo: user.companyMember?.companyLogo,
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
        getDateBirth(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", [
                    "user/company-member",
                    this.form.id,
                    this.form,
                ])
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
        onSuccessUploadDocument(e) {
            iziToast.success({
                title: "Selamat",
                message: "data anda berhasil diperbaharui",
                position: "topCenter",
            });
            this.getUser();
        },
    },
    components: { Success, UploadFileCompanyMember },
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
                            <label class="col-sm-3">No KTA</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    :disabled="true"
                                    :value="form.membershipCard"
                                />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Nama Pengguna</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': errors.name,
                                    }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.name"
                                    v-for="(error, index) in errors.name"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Email Pengguna</label>
                            <div class="col">
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="form.email"
                                    :class="{
                                        'is-invalid': errors.email,
                                    }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.email"
                                    v-for="(error, index) in errors.email"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Nama Perusahaan</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.companyName"
                                    :class="{
                                        'is-invalid': errors.companyName,
                                    }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.companyName"
                                    v-for="(error, index) in errors.companyName"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Nama Pimpinan</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.companyDirector"
                                    :class="{
                                        'is-invalid': errors.companyDirector,
                                    }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.companyDirector"
                                    v-for="(
                                        error, index
                                    ) in errors.companyDirector"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Alamat Perusahaan</label>
                            <div class="col">
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    v-model="form.companyAddress"
                                    :class="{
                                        'is-invalid': errors.companyAddress,
                                    }"
                                    :disabled="isLoading"
                                ></textarea>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.companyAddress"
                                    v-for="(
                                        error, index
                                    ) in errors.companyAddress"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Profil Perusahaan</label>
                            <div class="col">
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    v-model="form.companyProfile"
                                    :class="{
                                        'is-invalid': errors.companyProfile,
                                    }"
                                    :disabled="isLoading"
                                ></textarea>
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.companyProfile"
                                    v-for="(
                                        error, index
                                    ) in errors.companyProfile"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">No Telepon</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.phone"
                                    :class="{ 'is-invalid': errors.phone }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.phone"
                                    v-for="(error, index) in errors.phone"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">No Fax</label>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.facsmile"
                                    :class="{ 'is-invalid': errors.facsmile }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.facsmile"
                                    v-for="(error, index) in errors.facsmile"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
                                <div
                                    class="invalid-feedback"
                                    v-if="typeof errors == 'string'"
                                    v-html="errors"
                                ></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3">Email Perusahaan</label>
                            <div class="col">
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="form.companyEmail"
                                    :class="{
                                        'is-invalid': errors.companyEmail,
                                    }"
                                    :disabled="isLoading"
                                />
                                <div
                                    class="invalid-feedback"
                                    v-if="errors.companyEmail"
                                    v-for="(
                                        error, index
                                    ) in errors.companyEmail"
                                    :key="index"
                                >
                                    {{ error }}.
                                </div>
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

    <UploadFileCompanyMember
        :document="document"
        @onSuccess="onSuccessUploadDocument($e)"
    />

    <Success :url="{ name: 'My Profile' }" :msg="'data berhasil disimpan.'" />
</template>
