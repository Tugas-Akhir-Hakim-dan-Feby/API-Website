<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";
import Util from "../../../store/utils/util";

export default {
    props: ["id"],
    data() {
        return {
            isLoading: false,
            msg: "",
            form: {
                nip: "",
                name: "",
                email: "",
                phone: "",
                birthPlace: "",
                dateBirth: "",
                gender: "",
                position: "",
                address: "",
            },
            errors: {},
        };
    },
    mounted() {
        this.getUser();
        Util.removeInvalidClass();
    },
    methods: {
        getUser() {
            this.isLoading = true;

            this.$store
                .dispatch("showData", ["user/hub", this.id])
                .then((response) => {
                    this.isLoading = false;
                    this.setForm(response.data);
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        setForm(user) {
            this.form = {
                nip: user.adminHub?.nip,
                name: user.name,
                email: user.email,
                phone: user.adminHub?.phone,
                birthPlace: user.adminHub?.birthPlace,
                dateBirth: user.adminHub?.dateBirth,
                gender: user.adminHub?.gender,
                position: user.adminHub?.position,
                address: user.adminHub?.address,
            };
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["user/hub", this.id, this.form])
                .then((response) => {
                    $("#successModal").modal("show");
                    this.$emit("onCancel", true);
                    this.msg = "data berhasil diperbaharui.";
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
    },
    components: { PageTitle, Success },
};
</script>
<template>
    {{ user }}
    <PageTitle title="Edit Pengguna API Pusat">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'User Hub' }"
                    >Admin Pusat</router-link
                >
            </li>
            <li class="breadcrumb-item active">Edit Pengguna</li>
        </ol>
    </PageTitle>

    <div class="card">
        <form @submit.prevent="handleSubmit">
            <div class="card-body">
                <div class="mb-2">
                    <label>NIP</label>
                    <input
                        type="number"
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.nip }"
                        v-model="form.nip"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.nip"
                        v-for="(error, index) in errors.nip"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Nama Lengkap</label>
                    <input
                        type="text"
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.name }"
                        v-model="form.name"
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
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input
                        type="text"
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.email }"
                        v-model="form.email"
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
                </div>
                <div class="mb-2">
                    <label>Telepon</label>
                    <input
                        type="number"
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.phone }"
                        v-model="form.phone"
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
                </div>
                <div class="mb-2">
                    <label>Tempat Lahir</label>
                    <input
                        type="text"
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.birthPlace }"
                        v-model="form.birthPlace"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.birthPlace"
                        v-for="(error, index) in errors.birthPlace"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Tanggal Lahir</label>
                    <input
                        type="date"
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.dateBirth }"
                        v-model="form.dateBirth"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.dateBirth"
                        v-for="(error, index) in errors.dateBirth"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Jenis Kelamin</label>
                    <select
                        class="form-validation form-select"
                        :class="{ 'is-invalid': errors.gender }"
                        v-model="form.gender"
                        :disabled="isLoading"
                    >
                        <option value="" selected disabled></option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    <div
                        class="invalid-feedback"
                        v-if="errors.gender"
                        v-for="(error, index) in errors.gender"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Jabatan</label>
                    <input
                        type="text"
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.position }"
                        v-model="form.position"
                        :disabled="isLoading"
                    />
                    <div
                        class="invalid-feedback"
                        v-if="errors.position"
                        v-for="(error, index) in errors.position"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Alamat</label>
                    <textarea
                        class="form-validation form-control"
                        :class="{ 'is-invalid': errors.address }"
                        v-model="form.address"
                        :disabled="isLoading"
                    >
                    </textarea>
                    <div
                        class="invalid-feedback"
                        v-if="errors.address"
                        v-for="(error, index) in errors.address"
                        :key="index"
                    >
                        {{ error }}.
                    </div>
                </div>
            </div>
            <div class="card-footer border-top d-flex justify-content-between">
                <router-link
                    :to="{ name: 'User Hub' }"
                    class="btn btn-sm btn-secondary"
                    >Batal</router-link
                >
                <button class="btn btn-sm btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <Success :url="{ name: 'User Hub' }" :msg="msg" />
</template>
