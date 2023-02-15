<script>
export default {
    data() {
        return {
            skills: [
                {
                    id: 1,
                    skillName: "Welder Fillet",
                },
                {
                    id: 2,
                    skillName: "Welder Groove",
                },
                {
                    id: 3,
                    skillName: "Welder Pipe",
                },
            ],
            branches: [
                {
                    id: 1,
                    branchName: "Cabang 1",
                    branchAddress: "Jl. Raya No. 1",
                    branchPhone: "081234567890",
                },
                {
                    id: 2,
                    branchName: "Cabang 2",
                    branchAddress: "Jl. Raya No. 2",
                    branchPhone: "081234567890",
                },
                {
                    id: 3,
                    branchName: "Cabang 3",
                    branchAddress: "Jl. Raya No. 3",
                    branchPhone: "081234567890",
                },
            ],
        };
    },
    methods: {
        handleSubmit() {
            this.$emit("onSuccess", true);
        },
    },
};
</script>
<template>
    <div class="mb-2">
        <label for="fullname" class="form-label">Nama Lengkap</label>
        <input class="form-control" type="text" id="fullname" />
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Email</label>
        <input class="form-control" type="email" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label for="fullname" class="form-label">Umur</label>
        <input class="form-control" type="text" id="fullname" />
    </div>

    <div class="mb-2">
        <label>Keahlian</label>
        <select name="" id="" class="form-select">
            <option value=""></option>
            <option v-for="skill in skills" :value="skill.id">
                {{ skill.skillName }}
            </option>
        </select>
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Telepon</label>
        <input class="form-control" type="text" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label>Cabang</label>
        <select name="" id="" class="form-select">
            <option value=""></option>
            <option v-for="branch in branches" :value="branch.id">
                {{ branch.branchName }}
            </option>
        </select>
    </div>

    <div class="mb-2">
        <label for="">Alamat</label>
        <textarea name="" id="" class="form-control"></textarea>
    </div>

    <div class="mb-2 text-center">
        <button class="btn btn-primary form-control" @click="handleSubmit">
            Register
        </button>
    </div>
</template>
