<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";
import Util from "../../../store/utils/util";

export default {
    data() {
        return {
            msg: "",
            isLoading: false,
            errors: {},
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
        };
    },
    mounted() {
        Util.removeInvalidClass();
    },
    methods: {
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("postData", ["user/hub", this.form])
                .then((response) => {
                    $("#successModal").modal("show");
                    this.msg = "data berhasil ditambahkan.";
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
    <PageTitle title="Tambah Pengguna API Pusat">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'User Hub' }"
                    >Admin Pusat</router-link
                >
            </li>
            <li class="breadcrumb-item active">Tambah Pengguna</li>
        </ol>
    </PageTitle>

    <div class="card">
        <form @submit.prevent="handleSubmit">
            <div class="card-body">
                <div class="mb-2">
                    <label>NIP</label>
                    <input
                        type="number"
                        class="form-control form-validation"
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
                        class="form-control form-validation"
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
                        class="form-control form-validation"
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
                        class="form-control form-validation"
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
                        class="form-control form-validation"
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
                        class="form-control form-validation"
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
                        class="form-select form-validation"
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
                        class="form-control form-validation"
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
                        class="form-control form-validation"
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
