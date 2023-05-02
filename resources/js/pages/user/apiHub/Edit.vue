<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";

export default {
    props: ["id"],
    mounted(){

    },
    data() {
        return {
            user: {},
        };
    },
    methods: {
        onCancel() {
            this.$emit("onCancel", true);
        },
        getUser(){
            this.$store
            .dispatch("showData" ["user/hub", this.id])
            .then((response) => {
                this.user = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        },
        handleSubmit() {
            this.isLoading = true;
            this.error = {};
            this.$store
            .dispatch("updateData", ["user/hub", this.id, this.user])
            .then((response)=> {
                this.isLoading = false;
                    $("#successModal").modal("show");
                    this.$emit("onCancel", true);
            })
            .catch((error)=> {
                this.isLoading = false;
                this.errors = error.response.data.messages;
            });
        },
    },
    components: { PageTitle, Success },
};
</script>
<template>
    <PageTitle :title="'Edit Pengguna API Pusat'" />

    <div class="card">
        <form @submit.prevent="handleSubmit" method="post">
            <div class="card-body">
                <div class="mb-2">
                    <label>Nama Lengkap</label>
                    <input 
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="user.name"
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
                    v-model="user.email"
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
                    <label>Alamat</label>
                    <input 
                    type="text" 
                    class="form-control"
                    id="address"
                    v-model="user.address"
                    :class="{'is-invalid': errors.address}"
                    :disabled="isLoading"
                    />
                    <div class="invalid-feedback"
                        v-if="errors.address"
                        v-for="(error, index) in errors.address"
                        :key="index">
                        {{ error }}.
                    </div>
                </div>
                <div class="mb-2">
                    <label>Jabatan</label>
                    <input 
                    type="text" 
                    class="form-control"
                    id="position"
                    v-model="user.position"
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
                    v-model="user.phone"
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
                    v-model="user.status"
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
                    :to="{ name: 'User Hub' }"
                    class="btn btn-secondary"
                    :disabled="isLoading"
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

    <Success :url="{ name: 'User Hub' }" :msg="msg" />
</template>
