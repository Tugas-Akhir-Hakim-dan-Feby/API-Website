<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";

export default {
    props: ["id"],
    data() {
        return {
            form: {
                name: "",
                email: "",
                address: "",
                position: "",
                phone: "",
                status: "",
            },
            errors: {},
            isLoading: false,
            msg: "",
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        onCancel() {
            this.$emit("onCancel", true);
        },
        getUser(){
            this.isLoading = true;
            
            // this.$store
            //     .dispatch("showData" ["user/hub", this.id])
            //     .then((response) => {
            //         this.isLoading = false;
            //         this.setFrom(response.data);
            //     })
            //     .catch((error) => {
            //         console.log(error);
            //     });
            this.$store
            .dispatch("showData",["user/hub", this.id])
            .then((response)=>{
                this.isLoading = false;
                this.setFrom(response.data)
            })
            .catch((error) => {
                this.isLoading = false;
                console.log( error);
            })
        },
        setFrom(user){
            this.form = {
                name: user.name,
                email: user.email,
                address: user.adminHub?.address,
                position: user.adminHub?.position,
                phone: user.adminHub?.phone,
                status: user.adminHub?.status,
            }
        },
        handleSubmit() {
            this.isLoading = true;
            this.error = {};
            this.$store
            .dispatch("updateData", ["user/hub", this.id, this.form])
            .then((response)=> {
                this.isLoading = false;
                    $("#successModal").modal("show");
                    this.$emit("onCancel", true);
                    this.msg = "Data berhasil di update"
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
                    <label>Alamat</label>
                    <input 
                    type="text" 
                    class="form-control"
                    id="address"
                    v-model="form.address"
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
                <button 
                class="btn btn-secondary"
                @click="onCancel"
                :disabled="isLoading">
                    Batal
                </button>
                <button class="btn btn-success" v-if="!isLoading">Simpan</button>
                <button
                    class="btn btn-success"
                    type="button"
                    :disabled="isLoading"
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

    <Success :msg="'Data berhasil di update'" />
</template>
