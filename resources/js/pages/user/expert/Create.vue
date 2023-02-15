<script>
import PageTitle from "../../../components/PageTitle.vue";
import Success from "../../../components/notifications/Success.vue";

export default {
    data() {
        return {
            msg: "",
            skills: [
                {
                    id: 1,
                    skillName: "Welding Engineer",
                },
                {
                    id: 2,
                    skillName: "Welding Inspector",
                },
                {
                    id: 3,
                    skillName: "Welding Specialist",
                },
                {
                    id: 4,
                    skillName: "Welding Destructive Testing",
                },
                {
                    id: 5,
                    skillName: "Welding Non-Destructive Testing",
                },
            ],
        };
    },
    methods: {
        handleSubmit() {
            $("#successModal").modal("show");
            this.msg = "data berhasil ditambahkan.";
        },
    },
    components: { PageTitle, Success },
};
</script>
<template>
    <div class="container-fluid">
        <PageTitle :title="'Tambah Pengguna Pakar'" />

        <div class="card">
            <form @submit.prevent="handleSubmit">
                <div class="card-body">
                    <div class="mb-2">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div class="mb-2">
                        <label>Keahlian</label>
                        <select name="" id="" class="form-control">
                            <option value="">Pilih Keahlian</option>
                            <option v-for="skill in skills" :value="skill.id">
                                {{ skill.skillName }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Telepon</label>
                        <input type="text" class="form-control" />
                    </div>
                </div>
                <div
                    class="card-footer border-top d-flex justify-content-between"
                >
                    <router-link
                        :to="{ name: 'User Expert' }"
                        class="btn btn-secondary"
                        >Batal</router-link
                    >
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <Success :url="{ name: 'User Expert' }" :msg="msg" />
</template>
