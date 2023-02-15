<script>
import Success from "../../components/notifications/Success.vue";
import Company from "./register/Company.vue";
import Expert from "./register/Expert.vue";
import Member from "./register/Member.vue";

export default {
    data() {
        return {
            roleId: null,
            roles: [
                {
                    id: 1,
                    name: "Tim Pakar",
                },
                {
                    id: 2,
                    name: "Member Perusahaan",
                },
                {
                    id: 3,
                    name: "Member Welder",
                },
            ],
        };
    },
    mounted() {},
    methods: {
        chooseRole(event) {
            this.roleId = event.target.value;
        },
        onSuccess() {
            $("#successModal").modal("show");
        },
    },
    components: { Expert, Company, Member, Success },
};
</script>
<template>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-header py-3 text-center bg-primary">
                            <h3
                                class="text-white text-center mt-1 fw-bold text-uppercase"
                            >
                                Registrasi
                            </h3>
                        </div>

                        <div class="card-body p-4">
                            <div class="mb-2">
                                <label>Pilih Jenis Pendaftaran</label>
                                <select
                                    class="form-select"
                                    @change="chooseRole"
                                >
                                    <option value=""></option>
                                    <option
                                        v-for="role in roles"
                                        :value="role.id"
                                    >
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                            <Expert
                                v-if="roleId == 1"
                                @onSuccess="onSuccess($e)"
                            />
                            <Company
                                v-else-if="roleId == 2"
                                @onSuccess="onSuccess($e)"
                            />
                            <Member
                                v-else-if="roleId == 3"
                                @onSuccess="onSuccess($e)"
                            />
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">
                                    <router-link to="/auth/login" class=""
                                        >Sudah punya akun? silahkan
                                        login.</router-link
                                    >
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Success
        :msg="'cek email anda untuk melakukan verifikasi.'"
        :url="{ name: 'Login' }"
    />
</template>

<style>
#auth .main-footer {
    padding: 0;
    margin-top: 0;
}
</style>
