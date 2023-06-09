<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";

export default {
    data() {
        return {
            msg: "",
            branches: [],
            form:{
                name: "",
                email: "",
                branch: "",
                position: "",
                phone: "",
                status: "",
            },
            errors: {},
            isLoading: false,
        };
    },
    onCancel() {
            this.$emit("onCancel", true);
        },
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            
            this.$store
            .dispatch("postData", ["user/branch", this.form])
            .then((response) => {
                this.isLoading = false;
                $("#successModal").modal("show");
                this.msg = "data berhasil ditambahkan.";
                this.$emit("onCancel", true);
            })
            .catch((error) => {
                // this.isLoading = false;
                // this.errors = error.response.data.messages;
                console.log( error );
            });
        },
    components: { PageTitle, Success },
};
</script>
<template>
    <PageTitle :title="'Tambah Pengguna API Pusat'" />

    <div class="card">
        <form @submit.prevent="handleSubmit" method="post">
            <div class="card-body">
                <div class="mb-2">
                    <label>Nama Lengkap</label>
                    <input 
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="form.name"
                    :class="{'is-invalid': errors.name}"
                    :disabled="isLoading"
                    />
                    <div class="invalid-feedback"
                            v-if="errors.name"
                            v-for="(error, index) in errors.name"
                            :key="index">
                            {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input 
                    type="text" 
                    class="form-control"
                    id="email"
                    v-model="form.email"
                    :class="{'is-invalid': errors.email}"
                    :disabled="isLoading"
                    />
                    <div class="invalid-feedback"
                            v-if="errors.email"
                            v-for="(error, index) in errors.email"
                            :key="index">
                            {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Cabang</label>
                    <select 
                    name="" 
                    id="" 
                    class="form-control"
                    v-model="form.branch"
                    :class="{'is-invalid': errors.status}"
                    :disabled="isLoading"
                    >
                        <option value="">Pilih Cabang</option>
                        <option v-for="branch in branches" :value="branch.id">
                            {{ branch.branchName }}
                        </option>
                    </select>
                </div>
                <div class="invalid-feedback"
                        v-if="errors.branch"
                        v-for="(error, index) in errors.branch"
                        :key="index">
                        {{ error }}.
                    </div>
                <div class="mb-2">
                    <label>Jabatan</label>
                    <input 
                    type="text" 
                    class="form-control"
                    id="position"
                    v-model="form.position"
                    :class="{'is-invalid': errors.position}"
                    :disabled="isLoading"
                    />
                    <div class="invalid-feedback"
                        v-if="errors.position"
                        v-for="(error, index) in errors.position"
                        :key="index">
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Telepon</label>
                    <input 
                    type="text" 
                    class="form-control"
                    id="phone"
                    v-model="form.phone"
                    :class="{'is-invalid': errors.phone}"
                    :disabled="isLoading"
                    />
                    <div class="invalid-feedback"
                        v-if="errors.phone"
                        v-for="(error, index) in errors.phone"
                        :key="index">
                        {{ error }}.
                    </div>
                </div>
                <div>
                    <label>Status</label>
                    <select
                    class="form-select" 
                    aria-label="Default select example"
                    v-model="form.status"
                    :class="{'is-invalid': errors.status}"
                    :disabled="isLoading"
                    >
                            <option value="0">Aktif</option>
                            <option value="1">Tidak Aktif</option>
                    </select>
                    <div class="invalid-feedback"
                        v-if="errors.status"
                        v-for="(error, index) in errors.status"
                        :key="index">
                        {{ error }}.
                    </div>
                </div>
            </div>
            
            <div class="card-footer border-top d-flex justify-content-between">
                <router-link
                    :to="{ name: 'User Branch' }"
                    class="btn btn-secondary"
                    >Batal</router-link
                >
                <button class="btn btn-primary" v-if="!isLoading">Simpan</button>
                <button
                    class="btn btn-success"
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

    <Success :url="{ name: 'User Branch' }" :msg="msg" />
</template>
